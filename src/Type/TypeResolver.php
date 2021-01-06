<?php

namespace Reify\Type;

use Reify\Exceptions\ReifyException;
use Reify\Property;
use Reify\Type;
use Reify\Util\Future;

class TypeResolver
{
    public function resolve(string $type): Type
    {
        static $cache;

        if (is_null($cache)) {
            $cache = [];
        }

        if (isset($cache[$type])) {
            return $cache[$type];
        }

        if (Type::isScalarType($type)) {
            return new Type($type);
        }

        try {
            $reflect = new \ReflectionClass($type);
        } catch (\ReflectionException $exception) {
            throw new ReifyException("TypeResolver can not resolve not existing type $type");
        }

        $cache[$type] = new Type($reflect->getName(), new Future(function () use ($reflect) {
            $propertyResolver = new Property\PropertyResolver();
            return array_map([$propertyResolver, 'resolve'], $reflect->getProperties());
        }));

        return $cache[$type];
    }
}