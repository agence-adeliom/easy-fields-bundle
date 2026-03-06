<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use Adeliom\EasyFieldsBundle\Admin\Field\IconField;
use Adeliom\EasyFieldsBundle\Form\IconType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\IconField::class)]
final class IconFieldTest extends TestCase
{
    public function testFieldConfiguresExpectedDefaultsAndFormOptions(): void
    {
        $dto = IconField::new('icon', 'Icon')
            ->setRequired(true)
            ->setJsonUrl('/icons.json')
            ->setSelectButtonLabel('Select')
            ->setCancelButtonLabel('Cancel')
            ->setShowAllButtonLabel('Show all')
            ->setSearchPlaceholder('Search')
            ->setNotResultMessage('No result')
            ->setDeleteLabel('Delete')
            ->setFonts(['fa'])
            ->getAsDto();

        self::assertSame('icon', $dto->getProperty());
        self::assertSame('Icon', $dto->getLabel());
        self::assertSame(IconType::class, $dto->getFormType());
        self::assertSame('@EasyFields/crud/field/icon.html.twig', $dto->getTemplatePath());
        self::assertStringContainsString('field-easy-field-iconpicker', $dto->getCssClass());
        self::assertSame('col-md-8 col-xxl-7', $dto->getDefaultColumns());
        self::assertFalse($dto->getDisplayedOn()->has(Crud::PAGE_INDEX));
        self::assertTrue($dto->getFormTypeOption('required'));
        self::assertSame('/icons.json', $dto->getFormTypeOption('json_url'));
        self::assertSame('Select', $dto->getFormTypeOption('select_button'));
        self::assertSame('Cancel', $dto->getFormTypeOption('cancel_button'));
        self::assertSame('Show all', $dto->getFormTypeOption('show_all_button'));
        self::assertSame('Search', $dto->getFormTypeOption('search_placeholder'));
        self::assertSame('No result', $dto->getFormTypeOption('no_result_found'));
        self::assertSame('Delete', $dto->getFormTypeOption('delete_label'));
        self::assertSame(['fa'], $dto->getFormTypeOption('fonts'));
    }
}
