<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language;

use JsonSerializable;
use ReturnTypeWillChange;

class SourceLocation implements JsonSerializable
{
    /** @var int */
    public $line;

    /** @var int */
    public $column;

    /**
     * @param int $line
     * @param int $col
     */
    public function __construct($line, $col)
    {
        $this->line   = $line;
        $this->column = $col;
    }

    /**
     * @return int[]
     */
    public function toArray()
    {
        return [
            'line'   => $this->line,
            'column' => $this->column,
        ];
    }

    /**
     * @return int[]
     */
    public function toSerializableArray()
    {
        return $this->toArray();
    }

    /**
     * @return int[]
     */
    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toSerializableArray();
    }
}
