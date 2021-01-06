<?php

namespace Reify\Attributes;

#[\Attribute]
class Type
{
    public function __construct(
        public string $name
    ) {}
}