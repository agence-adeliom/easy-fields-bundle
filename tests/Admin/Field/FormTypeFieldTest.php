<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField::class)]
final class FormTypeFieldTest extends TestCase
{
    public function testFieldSetsPropertyLabelAndFormType(): void
    {
        $dto = FormTypeField::new('content', 'Content', TextareaType::class)->getAsDto();

        self::assertSame('content', $dto->getProperty());
        self::assertSame('Content', $dto->getLabel());
        self::assertSame(TextareaType::class, $dto->getFormType());
    }
}
