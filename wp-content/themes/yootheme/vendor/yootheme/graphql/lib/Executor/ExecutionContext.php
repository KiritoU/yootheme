<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Executor;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Executor\Promise\PromiseAdapter;
use YOOtheme\GraphQL\Language\AST\FragmentDefinitionNode;
use YOOtheme\GraphQL\Language\AST\OperationDefinitionNode;
use YOOtheme\GraphQL\Type\Schema;

/**
 * Data that must be available at all points during query execution.
 *
 * Namely, schema of the type system that is currently executing,
 * and the fragments defined in the query document.
 *
 * @internal
 */
class ExecutionContext
{
    /** @var Schema */
    public $schema;

    /** @var FragmentDefinitionNode[] */
    public $fragments;

    /** @var mixed */
    public $rootValue;

    /** @var mixed */
    public $contextValue;

    /** @var OperationDefinitionNode */
    public $operation;

    /** @var mixed[] */
    public $variableValues;

    /** @var callable */
    public $fieldResolver;

    /** @var Error[] */
    public $errors;

    /** @var PromiseAdapter */
    public $promiseAdapter;

    public function __construct(
        $schema,
        $fragments,
        $rootValue,
        $contextValue,
        $operation,
        $variableValues,
        $errors,
        $fieldResolver,
        $promiseAdapter
    ) {
        $this->schema         = $schema;
        $this->fragments      = $fragments;
        $this->rootValue      = $rootValue;
        $this->contextValue   = $contextValue;
        $this->operation      = $operation;
        $this->variableValues = $variableValues;
        $this->errors         = $errors ?? [];
        $this->fieldResolver  = $fieldResolver;
        $this->promiseAdapter = $promiseAdapter;
    }

    public function addError(Error $error)
    {
        $this->errors[] = $error;

        return $this;
    }
}
