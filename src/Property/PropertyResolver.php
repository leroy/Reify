<?php

namespace Reify\Property;

use ReflectionProperty;
use Reify\Exceptions\ReifyException;
use Reify\Property;
use Reify\Type;

class PropertyResolver
{
    public function resolve(ReflectionProperty $property): Property
    {
        static $cache;

        if (is_null($cache)) {
            $cache = [];
        }

        $type = $this->getType($property);

        $typeResolver = new Type\TypeResolver();

        return new Property(
            $typeResolver->resolve($property->getDeclaringClass()->name),
            $property->getName(),
            $typeResolver->resolve($type),
            array: Type::isArray($property->getType()->getName())
        );
    }

    private function getType(ReflectionProperty $property): string
    {
        if (is_null($property->getType())) {
            throw new ReifyException("No type definition found on property. ($property->class::$property->name)");
        }

        $type = $property->getType()->getName();

        if (Type::isArray($type)) {
            $attributes = $property->getAttributes(\Reify\Attributes\Type::class);

            if (empty($attributes)) {
                throw new ReifyException("No type definition found on property defined as array. ($property->class::$property->name)");
            }

            return $property->getAttributes(\Reify\Attributes\Type::class)[0]->newInstance()->name;
        }

        return $type;
    }
}