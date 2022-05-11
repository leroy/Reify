<?php

namespace Reify;

use Reify\Attributes\Construct;
use Reify\Attributes\Factory;
use Reify\Exceptions\ReifyException;
use Reify\Type\TypeResolver;

/**
 * @template T
 */
class Mapper
{
    public function __construct(private array $data)
    {
        $this->typeResolver = new TypeResolver();
    }

    /**
     * @param T $type
     * @return T
     */
    public function to(string $type): mixed
    {
        $type = $this->typeResolver->resolve($type);

        return $this->map($type, $this->data);
    }

    private function map(Type $type, array $data): mixed
    {
        if ($type::isObject($type->name)) {
            return (object)$data;
        }

        $object = $type->instance();

        foreach ($data as $key => $value) {
            try {
                $property = $type->getProperty($key);
            } catch (ReifyException $e) {
                continue;
            }

            $object->{$key} = $this->mapProperty($property, $value);
        }

        return $object;
    }

    private function mapProperty(Property $property, mixed $value): mixed
    {
        if (is_null($value) && !$property->isNullable()) {
            throw new ReifyException("{$property->getFullyQualifiedName()} is not declared nullable but no value was found the given data");
        }

        if ($property->isArray()) {
            return $this->mapArray($property, $value);
        }

        if ($property->hasAttribute(Construct::class)) {
            return $this->constructPropertyInstance($property, $value);
        }

        if ($property->hasAttribute(Factory::class)) {
            return $this->callFactory($property->getAttribute(Factory::class), $value);
        }

        if (!$property->type->isScalar()) {
            return $this->map($property->type, $value);
        }

        return $value;
    }

    private function mapArray(Property $property, array $data): array
    {
        return array_map(function (mixed $value) use ($property) {
            if ($property->hasAttribute(Construct::class)) {
                return $this->constructPropertyInstance($property, $value);
            }

            return $this->map($property->type, $value);
        }, $data);
    }

    private function constructPropertyInstance(Property $property, mixed $value): mixed
    {
        return $property->type->instance($value);
    }

    private function callFactory(Factory $factory, mixed $value): mixed
    {
        return $factory->call($value);
    }
}
