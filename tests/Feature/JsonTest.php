<?php

use Reify\Tests\Fixtures\Person;

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
    "firstname": "Leroy",
    "address": {
        "street": "Harry",
        "house_number": "1",
        "city": "Potter",
        "postal_code": "0000AA"
    }
}
JSON;

    $person = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\Person::class);

    expect($person->address)->toBeInstanceOf(\Reify\Tests\Fixtures\Address::class);
    expect($person->address->street)->toBe('Harry');
});

it('maps plain objects', function() {
    $json = <<<JSON
{
    "data": {
        "street": "Harry",
        "number": "1",
        "city": "Potter",
        "postalcode": "0000AA"
    }
}
JSON;

    $plainObject = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\PlainObject::class);

    expect($plainObject->data)->toBeObject();
    expect($plainObject->data->street)->toBe("Harry");
});

it('throws a JsonException on invalid json', function() {
    \Reify\Reify::json('invalid');
})->throws(JsonException::class);

it('constructs properties with the Construct attribute', function() {
    $json = <<<JSON
{
    "type": "PLAIN"
}
JSON;

    $constructedProperties = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\PropertyWithAttributes::class);

    expect($constructedProperties->type)->toBeInstanceOf(\Reify\Tests\Fixtures\Enum::class);
    expect($constructedProperties->type->value)->toBe("PLAIN");
});

it('constructs a list of properties with the Construct attribute', function() {
    $json = <<<JSON
{
    "types": ["TYPE_1", "TYPE_2", "TYPE_3"]
}
JSON;

    $constructedProperties = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\PropertyWithAttributes::class);

    expect($constructedProperties->types)->toBeArray();

    expect($constructedProperties->types[0])->toBeInstanceOf(\Reify\Tests\Fixtures\Enum::class);
    expect($constructedProperties->types[1])->toBeInstanceOf(\Reify\Tests\Fixtures\Enum::class);
    expect($constructedProperties->types[2])->toBeInstanceOf(\Reify\Tests\Fixtures\Enum::class);

    expect($constructedProperties->types[0]->value)->toBe("TYPE_1");
    expect($constructedProperties->types[1]->value)->toBe("TYPE_2");
    expect($constructedProperties->types[2]->value)->toBe("TYPE_3");
});

it('constructs a type from a factory', function() {
    $json = <<<JSON
{
    "person": {
        "firstname": "Leroy",
        "lastname": "Bakker"
    }
}
JSON;

    $typeWithFactory = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\TypeWithFactory::class);

    expect($typeWithFactory->person)->toBeInstanceOf(Person::class);
    expect($typeWithFactory->person->firstname)->toBe("Leroy");
    expect($typeWithFactory->person->lastname)->toBe("Bakker");
});

it('constructs a type from a type with a factory method', function() {
    $json = <<<JSON
{
    "person": {
        "firstname": "Leroy",
        "lastname": "Bakker"
    }
}
JSON;

    $typeWithFactory = \Reify\Reify::json($json)->to(\Reify\Tests\Fixtures\TypeWithFactoryMethod::class);

    expect($typeWithFactory->person)->toBeInstanceOf(Person::class);
    expect($typeWithFactory->person->firstname)->toBe("Leroy");
    expect($typeWithFactory->person->lastname)->toBe("Bakker");
});

