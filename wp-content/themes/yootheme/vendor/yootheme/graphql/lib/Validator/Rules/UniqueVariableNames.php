<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\NameNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\AST\VariableDefinitionNode;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class UniqueVariableNames extends ValidationRule
{
    /** @var NameNode[] */
    public $knownVariableNames;

    public function getVisitor(ValidationContext $context)
    {
        $this->knownVariableNames = [];

        return [
            NodeKind::OPERATION_DEFINITION => function () : void {
                $this->knownVariableNames = [];
            },
            NodeKind::VARIABLE_DEFINITION  => function (VariableDefinitionNode $node) use ($context) : void {
                $variableName = $node->variable->name->value;
                if (! isset($this->knownVariableNames[$variableName])) {
                    $this->knownVariableNames[$variableName] = $node->variable->name;
                } else {
                    $context->reportError(new Error(
                        self::duplicateVariableMessage($variableName),
                        [$this->knownVariableNames[$variableName], $node->variable->name]
                    ));
                }
            },
        ];
    }

    public static function duplicateVariableMessage($variableName)
    {
        return sprintf('There can be only one variable named "%s".', $variableName);
    }
}
