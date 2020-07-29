<?php

namespace Rector\Order\Tests\Rector\Class_\OrderPropertyByComplexityRector\Fixture;

class VisibilityClass
{
    /**
     * @var string
     */
    public $categoryName;

    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var int
     */
    private const PRICE = 100;

    /**
     * @var string
     */
    public const NAME = 'some name';
}

?>
-----
<?php

namespace Rector\Order\Tests\Rector\Class_\OrderPropertyByComplexityRector\Fixture;

class VisibilityClass
{
    /**
     * @var string
     */
    public const NAME = 'some name';
    /**
     * @var int
     */
    private const PRICE = 100;
    /**
     * @var string
     */
    public $categoryName;

    /**
     * @var int
     */
    private $categoryId;
}

?>
