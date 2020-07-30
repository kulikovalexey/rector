<?php

namespace Rector\Generic\Tests\Rector\Class_\AddConfigureClassMethodWithConstantRector\Fixture;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;

class SomeClass implements ConfigurableRectorInterface
{
    private $someTypes;

    public const SOME_TYPES = '$someTypes';
}

?>
-----
<?php

namespace Rector\Generic\Tests\Rector\Class_\AddConfigureClassMethodWithConstantRector\Fixture;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;

class SomeClass implements ConfigurableRectorInterface
{
    private $someTypes;

    public const SOME_TYPES = '$someTypes';
    public function configure(array $configuration): void
    {
        $this->someTypes = $configuration[self::SOME_TYPES] ?? [];
    }
}

?>
