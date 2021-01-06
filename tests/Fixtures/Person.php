<?php

namespace Reify\Tests\Fixtures;

use Reify\Attributes\Type;

class Person
{
    public string $firstname;

    public string $lastname;

    public string $email;

    public int $age;

    public Address $address;

    public ?Person $spouse;

    #[Type(Person::class)]
    public array $siblings;
}