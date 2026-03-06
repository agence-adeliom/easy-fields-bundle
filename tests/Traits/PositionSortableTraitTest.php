<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Traits;

use Adeliom\EasyFieldsBundle\Traits\PositionSortableTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasyFieldsBundle\Traits\PositionSortableTrait::class)]
final class PositionSortableTraitTest extends TestCase
{
    public function testTraitStoresSortableCoordinatesAndDynamicAccess(): void
    {
        $entity = new class() {
            use PositionSortableTrait;
        };

        $entity->setLft(1);
        $entity->setLvl(2);
        $entity->setRgt(3);
        $entity->setRoot(4);

        self::assertSame(1, $entity->getLft());
        self::assertSame(2, $entity->getLvl());
        self::assertSame(3, $entity->getRgt());
        self::assertSame(4, $entity->getRoot());
        self::assertSame(3, $entity->getSortableData('rgt'));
    }
}
