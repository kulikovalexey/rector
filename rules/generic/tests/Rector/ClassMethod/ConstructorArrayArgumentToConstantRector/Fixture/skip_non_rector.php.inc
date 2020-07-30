<?php

namespace Rector\Generic\Tests\Rector\ClassMethod\ConstructorArrayArgumentToConstantRector\Fixture;

class SkipNonRector
{
    private $someTypes;

    public function __construct(array $someTypes)
    {
        $this->someTypes = $someTypes;
    }
}
