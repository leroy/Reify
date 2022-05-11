<?php

namespace Reify\Tests\Fixtures;

use Reify\Attributes\Factory;

class TypeWithFactoryMethod
{
    #[Factory([Person::class, 'make'])]
    public Person $person;
}
