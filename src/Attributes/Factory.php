<?php

namespace Reify\Attributes;

use Attribute;

#[Attribute]
class Factory
{
    public $factory;

    public function __construct(
        callable|string $factory
    )
    {
        if (is_string($factory) && !class_exists($factory)) {
            throw new \InvalidArgumentException("Invalid factory callable: $factory");
        }

        $this->factory = $factory;
    }

    public function call(...$arguments): mixed
    {
        $callable = $this->factory;

        if (is_string($callable)) {
            $callable = new $callable;
        }

        return call_user_func($callable, ...$arguments);
    }
}
