<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator;

use YOOtheme\GraphQL\Error\InvariantViolation;
use YOOtheme\GraphQL\Language\AST\DocumentNode;
use YOOtheme\GraphQL\Language\AST\FieldNode;
use YOOtheme\GraphQL\Language\AST\FragmentDefinitionNode;
use YOOtheme\GraphQL\Language\AST\FragmentSpreadNode;
use YOOtheme\GraphQL\Language\AST\HasSelectionSet;
use YOOtheme\GraphQL\Language\AST\InlineFragmentNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\AST\OperationDefinitionNode;
use YOOtheme\GraphQL\Language\AST\SelectionSetNode;
use YOOtheme\GraphQL\Language\AST\VariableNode;
use YOOtheme\GraphQL\Language\Visitor;
use YOOtheme\GraphQL\Type\Definition\CompositeType;
use YOOtheme\GraphQL\Type\Definition\EnumType;
use YOOtheme\GraphQL\Type\Definition\FieldDefinition;
use YOOtheme\GraphQL\Type\Definition\InputObjectType;
use YOOtheme\GraphQL\Type\Definition\InputType;
use YOOtheme\GraphQL\Type\Definition\ListOfType;
use YOOtheme\GraphQL\Type\Definition\NonNull;
use YOOtheme\GraphQL\Type\Definition\OutputType;
use YOOtheme\GraphQL\Type\Definition\ScalarType;
use YOOtheme\GraphQL\Type\Definition\Type;
use YOOtheme\GraphQL\Type\Schema;
use YOOtheme\GraphQL\Utils\TypeInfo;
use SplObjectStorage;
use function array_merge;
use function array_pop;
use function assert;
use function count;

/**
 * An instance of this class is passed as the "this" context to all validators,
 * allowing access to commonly useful contextual information from within a
 * validation rule.
 */
class ValidationContext extends ASTValidationContext
{
    /** @var TypeInfo */
    private $typeInfo;

    /** @var FragmentDefinitionNode[] */
    private $fragments;

    /** @var SplObjectStorage */
    private $fragmentSpreads;

    /** @var SplObjectStorage */
    private $recursivelyReferencedFragments;

    /** @var SplObjectStorage */
    private $variableUsages;

    /** @var SplObjectStorage */
    private $recursiveVariableUsages;

    public function __construct(Schema $schema, DocumentNode $ast, TypeInfo $typeInfo)
    {
        parent::__construct($ast, $schema);
        $this->typeInfo                       = $typeInfo;
        $this->fragmentSpreads                = new SplObjectStorage();
        $this->recursivelyReferencedFragments = new SplObjectStorage();
        $this->variableUsages                 = new SplObjectStorage();
        $this->recursiveVariableUsages        = new SplObjectStorage();
    }

    /**
     * @return mixed[][] List of ['node' => VariableNode, 'type' => ?InputObjectType]
     */
    public function getRecursiveVariableUsages(OperationDefinitionNode $operation)
    {
        $usages = $this->recursiveVariableUsages[$operation] ?? null;

        if ($usages === null) {
            $usages    = $this->getVariableUsages($operation);
            $fragments = $this->getRecursivelyReferencedFragments($operation);

            $allUsages = [$usages];
            foreach ($fragments as $fragment) {
                $allUsages[] = $this->getVariableUsages($fragment);
            }
            $usages                                    = array_merge(...$allUsages);
            $this->recursiveVariableUsages[$operation] = $usages;
        }

        return $usages;
    }

    /**
     * @return mixed[][] List of ['node' => VariableNode, 'type' => ?InputObjectType]
     */
    private function getVariableUsages(HasSelectionSet $node)
    {
        $usages = $this->variableUsages[$node] ?? null;

        if ($usages === null) {
            $newUsages = [];
            $typeInfo  = new TypeInfo($this->schema);
            Visitor::visit(
                $node,
                Visitor::visitWithTypeInfo(
                    $typeInfo,
                    [
                        NodeKind::VARIABLE_DEFINITION => static function () : bool {
                            return false;
                        },
                        NodeKind::VARIABLE            => static function (VariableNode $variable) use (
                            &$newUsages,
                            $typeInfo
                        ) : void {
                            $newUsages[] = [
                                'node' => $variable,
                                'type' => $typeInfo->getInputType(),
                                'defaultValue' => $typeInfo->getDefaultValue(),
                            ];
                        },
                    ]
                )
            );
            $usages                      = $newUsages;
            $this->variableUsages[$node] = $usages;
        }

        return $usages;
    }

    /**
     * @return FragmentDefinitionNode[]
     */
    public function getRecursivelyReferencedFragments(OperationDefinitionNode $operation)
    {
        $fragments = $this->recursivelyReferencedFragments[$operation] ?? null;

        if ($fragments === null) {
            $fragments      = [];
            $collectedNames = [];
            $nodesToVisit   = [$operation];
            while (count($nodesToVisit) > 0) {
                $node    = array_pop($nodesToVisit);
                $spreads = $this->getFragmentSpreads($node);
                foreach ($spreads as $spread) {
                    $fragName = $spread->name->value;

                    if ($collectedNames[$fragName] ?? false) {
                        continue;
                    }

                    $collectedNames[$fragName] = true;
                    $fragment                  = $this->getFragment($fragName);
                    if (! $fragment) {
                        continue;
                    }

                    $fragments[]    = $fragment;
                    $nodesToVisit[] = $fragment;
                }
            }
            $this->recursivelyReferencedFragments[$operation] = $fragments;
        }

        return $fragments;
    }

    /**
     * @param OperationDefinitionNode|FragmentDefinitionNode $node
     *
     * @return FragmentSpreadNode[]
     */
    public function getFragmentSpreads(HasSelectionSet $node) : array
    {
        $spreads = $this->fragmentSpreads[$node] ?? null;
        if ($spreads === null) {
            $spreads = [];
            /** @var SelectionSetNode[] $setsToVisit */
            $setsToVisit = [$node->selectionSet];
            while (count($setsToVisit) > 0) {
                $set = array_pop($setsToVisit);

                foreach ($set->selections as $selection) {
                    if ($selection instanceof FragmentSpreadNode) {
                        $spreads[] = $selection;
                    } else {
                        assert($selection instanceof FieldNode || $selection instanceof InlineFragmentNode);
                        $selectionSet = $selection->selectionSet;
                        if ($selectionSet !== null) {
                            $setsToVisit[] = $selectionSet;
                        }
                    }
                }
            }
            $this->fragmentSpreads[$node] = $spreads;
        }

        return $spreads;
    }

    /**
     * @param string $name
     *
     * @return FragmentDefinitionNode|null
     */
    public function getFragment($name)
    {
        $fragments = $this->fragments;
        if (! $fragments) {
            $fragments = [];
            foreach ($this->getDocument()->definitions as $statement) {
                if (! ($statement instanceof FragmentDefinitionNode)) {
                    continue;
                }

                $fragments[$statement->name->value] = $statement;
            }
            $this->fragments = $fragments;
        }

        return $fragments[$name] ?? null;
    }

    public function getType() : ?OutputType
    {
        return $this->typeInfo->getType();
    }

    /**
     * @return (CompositeType & Type) | null
     */
    public function getParentType() : ?CompositeType
    {
        return $this->typeInfo->getParentType();
    }

    /**
     * @return (Type & InputType) | null
     */
    public function getInputType() : ?InputType
    {
        return $this->typeInfo->getInputType();
    }

    /**
     * @return (Type&InputType)|null
     */
    public function getParentInputType() : ?InputType
    {
        return $this->typeInfo->getParentInputType();
    }

    /**
     * @return FieldDefinition
     */
    public function getFieldDef()
    {
        return $this->typeInfo->getFieldDef();
    }

    public function getDirective()
    {
        return $this->typeInfo->getDirective();
    }

    public function getArgument()
    {
        return $this->typeInfo->getArgument();
    }
}
