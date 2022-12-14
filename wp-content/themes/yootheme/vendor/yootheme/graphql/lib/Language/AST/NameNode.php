<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language\AST;

class NameNode extends Node implements TypeNode
{
    /** @var string */
    public $kind = NodeKind::NAME;

    /** @var string */
    public $value;
}
