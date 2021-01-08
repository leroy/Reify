<?php

namespace Reify;

class Property
{
    public function __construct(
        public Type $declaringType,
        public string $name,
        public Type $type,
        public bool $array = false,
        public bool $nullable = false,
        private array $attributes = []
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

    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @template T
     * @param T $name
     * @return T
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name];
    }

    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]);
    }
}