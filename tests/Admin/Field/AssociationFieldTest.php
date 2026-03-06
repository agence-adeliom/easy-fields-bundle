<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\AssetDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\AssociationField::class)]
final class AssociationFieldTest extends TestCase
{
    public function testFieldConfiguresExpectedDefaultsAndMutators(): void
    {
        $field = AssociationField::new('category', 'Category')
            ->allowAdd()
            ->autocomplete()
            ->setCrudController('App\\Controller\\Admin\\CategoryCrudController')
            ->setButtonAddLabel('Add')
            ->setButtonAddIcon('plus')
            ->listSelector()
            ->listButtonIcon('list')
            ->listButtonLabel('Choose')
            ->listButtonCancelLabel('Cancel')
            ->listButtonValidateLabel('Validate')
            ->listShowFilter(false)
            ->listShowSearch(false);

        $dto = $field->getAsDto();

        self::assertSame('category', $dto->getProperty());
        self::assertSame('Category', $dto->getLabel());
        self::assertSame(EntityType::class, $dto->getFormType());
        self::assertSame('@EasyFields/crud/field/association.html.twig', $dto->getTemplatePath());
        self::assertStringContainsString('field-association', $dto->getCssClass());
        self::assertSame('App\\Controller\\Admin\\CategoryCrudController', $dto->getCustomOption(AssociationField::OPTION_CRUD_CONTROLLER));
        self::assertTrue($dto->getCustomOption(AssociationField::OPTION_AUTOCOMPLETE));
        self::assertTrue($dto->getCustomOption(AssociationField::OPTION_ALLOW_ADD));
        self::assertTrue($dto->getCustomOption(AssociationField::OPTION_LIST_SELECTOR));
        self::assertSame('Add', $dto->getCustomOption(AssociationField::OPTION_BUTTON_ADD_LABEL));
        self::assertSame('plus', $dto->getCustomOption(AssociationField::OPTION_BUTTON_ADD_ICON));
        self::assertSame('list', $dto->getCustomOption(AssociationField::OPTION_LIST_BUTTON_ICON));
        self::assertSame('Choose', $dto->getCustomOption(AssociationField::OPTION_LIST_BUTTON_LABEL));
        self::assertSame('Cancel', $dto->getCustomOption(AssociationField::OPTION_LIST_BUTTON_CANCEL_LABEL));
        self::assertSame('Validate', $dto->getCustomOption(AssociationField::OPTION_LIST_BUTTON_VALIDATE_LABEL));
        self::assertFalse($dto->getCustomOption(AssociationField::OPTION_LIST_SHOW_FILTER));
        self::assertFalse($dto->getCustomOption(AssociationField::OPTION_LIST_SHOW_SEARCH));
        self::assertCount(2, $dto->getAssets()->getJsAssets());
        self::assertCount(1, $dto->getAssets()->getCssAssets());
        self::assertContainsOnlyInstancesOf(AssetDto::class, $dto->getAssets()->getCssAssets());
        self::assertSame([
            AssociationField::OPTION_BUTTON_ADD_LABEL,
            AssociationField::OPTION_BUTTON_ADD_ICON,
            AssociationField::OPTION_ALLOW_ADD,
            AssociationField::OPTION_LIST_SELECTOR,
            AssociationField::OPTION_LIST_BUTTON_LABEL,
            AssociationField::OPTION_LIST_BUTTON_ICON,
            AssociationField::OPTION_LIST_BUTTON_CANCEL_LABEL,
            AssociationField::OPTION_LIST_BUTTON_VALIDATE_LABEL,
            AssociationField::OPTION_LIST_SHOW_FILTER,
            AssociationField::OPTION_LIST_SHOW_SEARCH,
            AssociationField::OPTION_LIST_FILTERS,
            AssociationField::OPTION_LIST_DISPLAY_COLUMNS,
        ], AssociationField::getSettableOptions());
    }
}
