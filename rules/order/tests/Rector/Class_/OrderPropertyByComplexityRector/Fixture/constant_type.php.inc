<?php

namespace Rector\Order\Tests\Rector\Class_\OrderPropertyByComplexityRector\Fixture;

class ConstantTypesClass
{
    /**
     * @var string
     */
    private $categoryName;

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
    private const NAME = 'some name';

}

?>
-----
<?php

namespace Rector\Order\Tests\Rector\Class_\OrderPropertyByComplexityRector\Fixture;

class ConstantTypesClass
{
    /**
     * @var string
     */
    private const NAME = 'some name';
    /**
     * @var int
     */
    private const PRICE = 100;
    /**
     * @var int
     */
    private $categoryId;
    /**
     * @var string
     */
    private $categoryName;
}

?>
