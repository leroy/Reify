<?php

namespace Reify\Util;

use Closure;

class Future
{
    private $value;

    public function __construct(private Closure $resolve) {}

    public function getValue(): mixed
    {
        if (is_null($this->value)) {
            $this->value = call_user_func($this->resolve);
        }

        return $this->value;
    }
}