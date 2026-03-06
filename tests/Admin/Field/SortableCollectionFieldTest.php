<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use Adeliom\EasyFieldsBundle\Admin\Field\SortableCollectionField;
use Adeliom\EasyFieldsBundle\Form\SortableCollectionType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\SortableCollectionField::class)]
final class SortableCollectionFieldTest extends TestCase
{
    public function testFieldConfiguresExpectedDefaultsAndMutators(): void
    {
        $dto = SortableCollectionField::new('items', 'Items')
            ->setEntryType(TextareaType::class)
            ->allowDrag(false)
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryIsComplex(true)
            ->showEntryLabel()
            ->renderExpanded()
            ->getAsDto();

        self::assertSame('items', $dto->getProperty());
        self::assertSame('Items', $dto->getLabel());
        self::assertSame(SortableCollectionType::class, $dto->getFormType());
        self::assertSame('@EasyFields/crud/field/sortable_collection.html.twig', $dto->getTemplatePath());
        self::assertStringContainsString('field-collection_sortable', $dto->getCssClass());
        self::assertSame('col-md-8 col-xxl-7', $dto->getDefaultColumns());
        self::assertFalse($dto->getCustomOption(SortableCollectionField::OPTION_ALLOW_DRAG));
        self::assertFalse($dto->getCustomOption(SortableCollectionField::OPTION_ALLOW_ADD));
        self::assertFalse($dto->getCustomOption(SortableCollectionField::OPTION_ALLOW_DELETE));
        self::assertTrue($dto->getCustomOption(SortableCollectionField::OPTION_ENTRY_IS_COMPLEX));
        self::assertSame(TextareaType::class, $dto->getCustomOption(SortableCollectionField::OPTION_ENTRY_TYPE));
        self::assertTrue($dto->getCustomOption(SortableCollectionField::OPTION_SHOW_ENTRY_LABEL));
        self::assertTrue($dto->getCustomOption(SortableCollectionField::OPTION_RENDER_EXPANDED));
        self::assertCount(1, $dto->getAssets()->getJsAssets());
        self::assertCount(1, $dto->getAssets()->getCssAssets());
    }
}
