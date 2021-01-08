<?php

namespace Reify\Tests\Fixtures;

use Reify\Attributes\Construct;
use Reify\Attributes\Type;

class PropertyWithAttributes
{
    #[Construct]
    public Enum $type;

    #[Type(Enum::class), Construct]
    public array $types;

    #[Type(Person::class)]
    public array $siblings;
}