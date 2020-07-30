<?php

namespace Rector\Generic\Tests\Rector\ClassMethod\ConstructorArrayArgumentToConstantRector\Fixture;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\RectorDefinition\RectorDefinition;
use stdClass;

class SkipObjectTypedArray implements RectorInterface
{
    private $someTypes;

    /**
     * @param stdClass[] $someTypes
     */
    public function __construct(array $someTypes)
    {
        $this->someTypes = $someTypes;
    }
    public function getDefinition(): RectorDefinition
    {
    }
}
