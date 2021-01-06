<?php

it('maps all properties', function() {
    $json = <<<JSON
{
    "firstname": "Leroy",
    "lastname": "Bakker"
}
JSON;

    $person = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\Person::class);

    expect($person)->toBeInstanceOf(\Reify\Tests\Fixtures\Person::class);
    expect($person->firstname)->toBe('Leroy');
});

it('maps strings correctly', function() {
    $json = <<<JSON
{
    "string": "Hello world"
}
JSON;

    $types = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\Types::class);

    expect($types->string)->toBe('Hello world');
    expect($types->string)->toBeString();
});

it('maps integers correctly', function() {
    $json = <<<JSON
{
    "int": 1
}
JSON;

    $types = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\Types::class);

    expect($types->int)->toBe(1);
    expect($types->int)->toBeInt();
});

it('maps floats correctly', function() {
    $json = <<<JSON
{
    "float": 0.89
}
JSON;

    $types = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\Types::class);

    expect($types->float)->toBe(0.89);
    expect($types->float)->toBeFloat();
});

it('maps booleans correctly', function() {
    $json = <<<JSON
{
    "bool": false
}
JSON;

    $types = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\Types::class);

    expect($types->bool)->toBe(false);
    expect($types->bool)->toBeBool();
});

it('throws an exception when trying to map a null value to a non-nullable property', function() {
    $json = <<<JSON
{
    "value": null
}
JSON;

    \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\NotNullable::class);
})->throws(\Reify\Exceptions\ReifyException::class);

it('maps nested objects', function() {
    $json = <<<JSON
{
    "name": "Leroy",
    "address": {
        "street": "Harry",
        "number": "1",
        "city": "Potter",
        "postalcode": "0000AA"
    }
}
JSON;

    $person = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\Person::class);

    expect($person->address)->toBeInstanceOf(\Reify\Tests\Fixtures\Address::class);
    expect($person->address->street)->toBe('Harry');
});