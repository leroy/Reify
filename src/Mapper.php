<?php

namespace Reify;


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
            return (object) $data;
        }

        $object = $type->instance();

        foreach ($data as $key => $value) {
            try {
                $property = $type->getProperty($key);
            } catch (ReifyException $e) {
                continue;
            }

            if (is_null($value) && !$property->isNullable())
            {
                throw new ReifyException("{$property->getFullyQualifiedName()} is not declared nullable but no value was found the given data");
            }

            if (!$property->type->isScalar()) {
                $value = $this->map($property->type, $value);
            }

            if ($property->isArray()) {
                $value = $this->mapArray($property, $value);
            }

            $object->{$key} = $value;
        }

        return $object;
    }

    private function mapArray(Property $property, array $data): array
    {
        return array_map(fn(mixed $value) => $this->map($property->type, $value), $data);
    }
}