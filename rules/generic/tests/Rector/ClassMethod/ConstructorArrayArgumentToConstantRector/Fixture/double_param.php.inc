<?php

namespace Rector\Generic\Tests\Rector\ClassMethod\ConstructorArrayArgumentToConstantRector\Fixture;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\RectorDefinition\RectorDefinition;

class DoubleParam implements RectorInterface
{
    private $someTypes;
    private $moreTypes;

    public function __construct(array $someTypes, array $moreTypes)
    {
        $this->someTypes = $someTypes;
        $this->moreTypes = $moreTypes;
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

class DoubleParam implements RectorInterface, \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    private $someTypes;
    private $moreTypes;
    /**
     * @var string
     */
    public const SOME_TYPES = '$someTypes';
    /**
     * @var string
     */
    public const MORE_TYPES = '$moreTypes';

    public function getDefinition(): RectorDefinition
    {
    }
}

?>
