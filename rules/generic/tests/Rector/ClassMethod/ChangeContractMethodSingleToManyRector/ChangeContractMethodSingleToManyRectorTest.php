<?php

declare(strict_types=1);

namespace Rector\Generic\Tests\Rector\ClassMethod\ChangeContractMethodSingleToManyRector;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Generic\Rector\ClassMethod\ChangeContractMethodSingleToManyRector;
use Rector\Generic\Tests\Rector\ClassMethod\ChangeContractMethodSingleToManyRector\Source\OneToManyInterface;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ChangeContractMethodSingleToManyRectorTest extends AbstractRectorTestCase
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

    protected function getRectorsWithConfiguration(): array
    {
        return [
            ChangeContractMethodSingleToManyRector::class => [
                '$oldToNewMethodByType' => [
                    OneToManyInterface::class => [
                        'getNode' => 'getNodes',
                    ],
                ],
            ],
        ];
    }
}
