<?php

namespace Tests;

// ..

function reflect(string $class): \ReflectionClass
{
    return new \ReflectionClass($class);
}
