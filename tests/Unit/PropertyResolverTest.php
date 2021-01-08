<?php

beforeEach(function() {
    $this->reflect = new ReflectionClass(\Reify\Tests\Fixtures\Person::class);
    $this->resolver = new \Reify\Property\PropertyResolver();
});

it('a property has a reference to the correct declaring class', function() {
    $resolvedProperty = $this->resolver->resolve($this->reflect->getProperty('firstname'));

    expect($resolvedProperty->declaringType->name)->toBe(\Reify\Tests\Fixtures\Person::class);
});

it('maps a scalar property', function() {
    $resolvedProperty = $this->resolver->resolve($this->reflect->getProperty('firstname'));

    expect($resolvedProperty->name)->toBe('firstname');
    expect($resolvedProperty->type->name)->toBe('string');
    expect($resolvedProperty->array)->toBe(false);
    expect($resolvedProperty->nullable)->toBe(false);
});

it('maps a complex property', function() {
    $resolvedProperty = $this->resolver->resolve($this->reflect->getProperty('address'));

    expect($resolvedProperty->name)->toBe('address');
    expect($resolvedProperty->type->name)->toBe(\Reify\Tests\Fixtures\Address::class);
    expect($resolvedProperty->array)->toBe(false);
    expect($resolvedProperty->nullable)->toBe(false);
});

it('maps a array property', function() {
    $resolvedProperty = $this->resolver->resolve($this->reflect->getProperty('siblings'));

    expect($resolvedProperty->array)->toBe(true);
});

it('maps a nullable property', function() {
    $resolvedProperty = $this->resolver->resolve(\Tests\reflect(\Reify\Tests\Fixtures\Nullable::class)->getProperty('value'));

    expect($resolvedProperty->nullable)->toBe(true);
});

it('maps a list complex property', function() {
    $resolvedProperty = $this->resolver->resolve($this->reflect->getProperty('siblings'));

    expect($resolvedProperty->type->name)->toBe(\Reify\Tests\Fixtures\Person::class);
    expect($resolvedProperty->array)->toBe(true);
});

it('Throws an exception when there is no type definition', function() {
    $this->resolver->resolve(\Tests\reflect(\Reify\Tests\Fixtures\NoTypes::class)->getProperty('name'));
})->throws(\Reify\Exceptions\ReifyException::class);

it('Throws an exception when the type is array but there is no attribute', function() {
    $this->resolver->resolve(\Tests\reflect(\Reify\Tests\Fixtures\ArrayWithoutType::class)->getProperty('list'));
})->throws(\Reify\Exceptions\ReifyException::class);

it('maps all the attributes', function() {
    $resolvedProperty = $this->resolver->resolve(\Tests\reflect(\Reify\Tests\Fixtures\PropertyWithAttributes::class)->getProperty('types'));

    expect($resolvedProperty->getAttributes())->toHaveCount(2);
    expect($resolvedProperty->getAttribute(\Reify\Attributes\Construct::class))->not()->toBeNull();
    expect($resolvedProperty->getAttribute(\Reify\Attributes\Type::class))->not()->toBeNull();
});