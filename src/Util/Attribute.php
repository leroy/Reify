<?php

namespace Reify\Util;

use ReflectionClass;
use ReflectionProperty;

class Attribute
{
    public static function getAttributes(ReflectionClass|ReflectionProperty $reflection)
    {
        $attributes = [];

        foreach($reflection->getAttributes() as $attribute) {
            $attributes[$attribute->getName()] = $attribute->newInstance();
        }

        return $attributes;
    }

    /**
     * @template T
     * @param ReflectionClass|ReflectionProperty $reflection
     * @param T $attributeClass
     * @return ?T
     */
    public static function getAttribute(ReflectionClass|ReflectionProperty $reflection, string $attributeClass)
    {
        return $reflection->getAttributes($attributeClass)[0]?->newInstance();
    }
}