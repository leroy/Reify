<?php

namespace Reify;

use Reify\Exceptions\ReifyException;
use Reify\Util\Future;

class Type
{
    private array $properties = [];

    public const TYPES = [
        'bool',
        'boolean',
        'float',
        'int',
        'integer',
        'string'
    ];

    /**
     * @template T
     * @param T $name
     */
    public function __construct(
        public string $name,
        private ?Future $futureProperties = null
    )
    {
        if ($this->isScalar() && !empty($this->properties)) {
            throw new ReifyException('A scalar type can not contain properties');
        }
    }

    public function getProperties(): array
    {
        if (empty($this->properties)) {
            foreach ($this->futureProperties->getValue() as $property) {
                $this->properties[$property->name] = $property;
            }
        }

        return $this->properties;
    }

    public function getProperty(string $name): Property
    {
        if (!$this->hasProperty($name)) {
            throw new ReifyException("Property $name does not exist on type $this->name");
        }

        return $this->getProperties()[$name];
    }

    public function hasProperty($name): bool
    {
        return isset($this->getProperties()[$name]);
    }

    public function isScalar(): bool
    {
        return self::isScalarType($this->name);
    }

    public static function isScalarType(string $name): bool
    {
        return in_array($name, self::TYPES);
    }

    public static function isObject(string $name): bool
    {
        return $name === 'object';
    }

    public static function isArray(string $name): bool
    {
        return $name === 'array';
    }

    /**
     * @return T
     */
    public function instance(): mixed
    {
        return new $this->name;
    }
}