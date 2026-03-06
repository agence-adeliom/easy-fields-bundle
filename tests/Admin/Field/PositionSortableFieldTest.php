<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use Adeliom\EasyFieldsBundle\Admin\Field\PositionSortableField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\PositionSortableField::class)]
final class PositionSortableFieldTest extends TestCase
{
    public function testFieldConfiguresExpectedDefaultsAndOptions(): void
    {
        $dto = PositionSortableField::new('position', 'Position')
            ->setParentProperty('menu')
            ->setActionUrl('/admin/sort')
            ->getAsDto();

        self::assertSame('position', $dto->getProperty());
        self::assertSame('Position', $dto->getLabel());
        self::assertSame(NumberType::class, $dto->getFormType());
        self::assertSame('@EasyFields/form/form-easy-field-position-sortable.html.twig', $dto->getTemplatePath());
        self::assertSame('menu', $dto->getCustomOption(PositionSortableField::PARENT_PROPERTY));
        self::assertSame('/admin/sort', $dto->getCustomOption(PositionSortableField::ACTION_URL));
    }
}
