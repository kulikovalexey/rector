<?php

declare(strict_types=1);

namespace Rector\Generic\Tests\Rector\Class_\ParentClassToTraitsRector;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Generic\Rector\Class_\ParentClassToTraitsRector;
use Rector\Generic\Tests\Rector\Class_\ParentClassToTraitsRector\Source\AnotherParentObject;
use Rector\Generic\Tests\Rector\Class_\ParentClassToTraitsRector\Source\ParentObject;
use Rector\Generic\Tests\Rector\Class_\ParentClassToTraitsRector\Source\SecondTrait;
use Rector\Generic\Tests\Rector\Class_\ParentClassToTraitsRector\Source\SomeTrait;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ParentClassToTraitsRectorTest extends AbstractRectorTestCase
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

    /**
     * @return mixed[]
     */
    protected function getRectorsWithConfiguration(): array
    {
        return [
            ParentClassToTraitsRector::class => [
                '$parentClassToTraits' => [
                    ParentObject::class => [SomeTrait::class],
                    AnotherParentObject::class => [SomeTrait::class, SecondTrait::class],
                ],
            ],
        ];
    }
}
