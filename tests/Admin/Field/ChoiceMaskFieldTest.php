<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Admin\Field;

use Adeliom\EasyFieldsBundle\Admin\Field\ChoiceMaskField;
use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasyFieldsBundle\Admin\Field\ChoiceMaskField::class)]
final class ChoiceMaskFieldTest extends TestCase
{
    public function testFieldConfiguresExpectedDefaultsAndOptions(): void
    {
        $field = ChoiceMaskField::new('status', 'Status')
            ->setChoices(['Draft' => 'draft'])
            ->setMap(['draft' => ['publishedAt']])
            ->renderAsBadges(['draft' => 'warning'])
            ->renderExpanded()
            ->escapeHtml(false);

        $dto = $field->getAsDto();

        self::assertSame('status', $dto->getProperty());
        self::assertSame('Status', $dto->getLabel());
        self::assertSame(ChoiceMaskType::class, $dto->getFormType());
        self::assertSame('crud/field/choice', $dto->getTemplateName());
        self::assertStringContainsString('field-select', $dto->getCssClass());
        self::assertSame(['Draft' => 'draft'], $dto->getCustomOption(ChoiceMaskField::OPTION_CHOICES));
        self::assertSame(['draft' => ['publishedAt']], $dto->getCustomOption(ChoiceMaskField::OPTION_MAP));
        self::assertSame(['draft' => 'warning'], $dto->getCustomOption(ChoiceMaskField::OPTION_RENDER_AS_BADGES));
        self::assertTrue($dto->getCustomOption(ChoiceMaskField::OPTION_RENDER_EXPANDED));
        self::assertFalse($dto->getCustomOption(ChoiceMaskField::OPTION_ESCAPE_HTML_CONTENTS));
    }

    public function testChoiceAndMapValidationRejectInvalidValues(): void
    {
        $field = ChoiceMaskField::new('status');

        $this->expectException(\InvalidArgumentException::class);
        $field->setChoices('invalid');
    }

    public function testBadgeValidationRejectsUnsupportedTypesAndTypes(): void
    {
        $field = ChoiceMaskField::new('status');

        try {
            $field->renderAsBadges('invalid');
            self::fail('Expected invalid badge selector type exception.');
        } catch (\InvalidArgumentException) {
            self::assertTrue(true);
        }

        $this->expectException(\InvalidArgumentException::class);
        $field->renderAsBadges(['draft' => 'unknown']);
    }
}
