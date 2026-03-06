<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Valid;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\TranslationField::class)]
final class TranslationFieldTest extends TestCase
{
    public function testFieldConfiguresExpectedDefaultsAndRequiredOption(): void
    {
        $dto = TranslationField::new('translations', 'Translations', ['title' => ['field_type' => 'text']])
            ->setRequired(false)
            ->getAsDto();

        self::assertSame('translations', $dto->getProperty());
        self::assertSame('Translations', $dto->getLabel());
        self::assertSame(TranslationsType::class, $dto->getFormType());
        self::assertSame('@EasyFields/crud/field/translation.html.twig', $dto->getTemplatePath());
        self::assertFalse($dto->getDisplayedOn()->has(Crud::PAGE_INDEX));
        self::assertFalse($dto->getDisplayedOn()->has(Crud::PAGE_DETAIL));
        self::assertFalse($dto->getFormTypeOption('required'));
        self::assertSame(['title' => ['field_type' => 'text']], $dto->getFormTypeOption('fields'));
        self::assertCount(1, $dto->getFormTypeOption('constraints'));
        self::assertInstanceOf(Valid::class, $dto->getFormTypeOption('constraints')[0]);
    }
}
