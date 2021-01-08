<?php

namespace Reify\Property;

use ReflectionProperty;
use Reify\Exceptions\ReifyException;
use Reify\Property;
use Reify\Type;
use Reify\Util\Attribute;

class PropertyResolver
{
    public function resolve(ReflectionProperty $property): Property
    {
        static $cache;

        if (is_null($cache)) {
            $cache = [];
        }

        $declaringClass = $property->getDeclaringClass()->name;
        $propertyName = $property->getName();

        $key = "$declaringClass::$propertyName";

        if (!isset($cache[$key])) {
            $type = $this->getType($property);

            $typeResolver = new Type\TypeResolver();

            $cache[$key] = new Property(
                $typeResolver->resolve($declaringClass),
                $property->getName(),
                $typeResolver->resolve($type),
                array: Type::isArray($property->getType()->getName()),
                nullable: $property->getType()->allowsNull(),
                attributes: Attribute::getAttributes($property)

            );
        }

        return $cache[$key];
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