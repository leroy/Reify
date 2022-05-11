<?php

namespace Reify\Tests\Fixtures;

class PersonFactory
{
    public function __invoke(array $data): Person
    {
        $person = new Person();

        $person->firstname = $data['firstname'] ?? '';
        $person->lastname = $data['lastname'] ?? '';

        return $person;
    }
}
