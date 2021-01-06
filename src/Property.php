<?php

namespace Reify;

use Reify\Type;

class Property
{
    public function __construct(
        public Type $declaringType,
        public string $name,
        public Type $type,
        public bool $array = false,
        public bool $nullable = false,
    ) {}

    public function isArray(): bool
    {
        return $this->array;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getFullyQualifiedName(): string
    {
        return "{$this->declaringType->name}::{$this->name}";
    }
}