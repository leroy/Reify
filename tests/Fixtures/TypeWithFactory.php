<?php

namespace Reify\Tests\Fixtures;

use Reify\Attributes\Factory;

class TypeWithFactory
{
    #[Factory(PersonFactory::class)]
    public Person $person;
}
