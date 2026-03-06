<?php

namespace Adeliom\EasyFieldsBundle\Tests\Form;

use PHPUnit\Framework\Attributes\CoversClass;
use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\ChoiceMaskType::class)]
final class ChoiceMaskTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        return [
            new PreloadedExtension([new ChoiceMaskType()], []),
        ];
    }

    public function testTypeSanitizesMappedFieldNamesForView(): void
    {
        $form = $this->factory->create(ChoiceMaskType::class, null, [
            'choices' => [
                'Alpha' => 'alpha',
                'Beta' => 'beta',
            ],
            'map' => [
                'alpha' => ['group.title', 'group__body'],
                'beta' => ['content.main'],
            ],
        ]);

        $view = $form->createView();

        self::assertSame('choice_field_mask', $form->getConfig()->getType()->getInnerType()->getBlockPrefix());
        self::assertSame(
            [
                'alpha' => ['group__title', 'group____body'],
                'beta' => ['content__main'],
            ],
            $view->vars['map']
        );
        self::assertSame(['group__title', 'group____body', 'content__main'], $view->vars['all_fields']);
    }
}
