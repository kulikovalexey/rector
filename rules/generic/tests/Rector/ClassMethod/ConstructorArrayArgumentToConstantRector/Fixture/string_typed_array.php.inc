<?php

namespace Rector\Generic\Tests\Rector\ClassMethod\ConstructorArrayArgumentToConstantRector\Fixture;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\RectorDefinition\RectorDefinition;

class StringTypedArray implements RectorInterface
{
    private $someTypes;

    /**
     * @param string[] $someTypes
     */
    public function __construct(array $someTypes)
    {
        $this->someTypes = $someTypes;
    }
    public function getDefinition(): RectorDefinition
    {
    }
}

?>
-----
<?php

namespace Rector\Generic\Tests\Rector\ClassMethod\ConstructorArrayArgumentToConstantRector\Fixture;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\RectorDefinition\RectorDefinition;

class StringTypedArray implements RectorInterface, \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    private $someTypes;

    /**
     * @var string
     */
    public const SOME_TYPES = '$someTypes';
    public function getDefinition(): RectorDefinition
    {
    }
}

?>
