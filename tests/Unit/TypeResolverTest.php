<?php

it('resolves all properties', function() {

    $resolver = new \Reify\Type\TypeResolver();
    $type = $resolver->resolve(\Reify\Tests\Fixtures\Person::class);

    expect($type->getProperties())->toHaveCount(7);
});

it('resolves nested types', function() {
    $resolver = new \Reify\Type\TypeResolver();
    $type = $resolver->resolve(\Reify\Tests\Fixtures\Person::class);

    expect($type->getProperty('spouse')->type->name)->toBe(\Reify\Tests\Fixtures\Person::class);
    expect($type->getProperty('spouse')->type->getProperties())->toHaveCount(7);

});