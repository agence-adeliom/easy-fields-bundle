<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use Adeliom\EasyFieldsBundle\Admin\Field\OembedField;
use Adeliom\EasyFieldsBundle\Form\OembedType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\OembedField::class)]
final class OembedFieldTest extends TestCase
{
    public function testFieldConfiguresExpectedDefaultsAndRequiredOption(): void
    {
        $dto = OembedField::new('video', 'Video')
            ->setRequired(true)
            ->getAsDto();

        self::assertSame('video', $dto->getProperty());
        self::assertSame('Video', $dto->getLabel());
        self::assertSame(OembedType::class, $dto->getFormType());
        self::assertSame('@EasyFields/crud/field/oembed.html.twig', $dto->getTemplatePath());
        self::assertFalse($dto->getDisplayedOn()->has(Crud::PAGE_INDEX));
        self::assertTrue($dto->getFormTypeOption('required'));
    }
}
