<?php

declare(strict_types=1);

namespace Rector\Generic\Tests\Rector\Class_\AddConfigureClassMethodWithConstantRector;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Generic\Rector\Class_\AddConfigureClassMethodWithConstantRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class AddConfigureClassMethodWithConstantRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return AddConfigureClassMethodWithConstantRector::class;
    }
}
