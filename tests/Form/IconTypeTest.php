<?php

namespace Adeliom\EasyFieldsBundle\Tests\Form;

use Adeliom\EasyFieldsBundle\Form\IconType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\IconType::class)]
final class IconTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        return [
            new PreloadedExtension([new IconType()], []),
        ];
    }

    public function testTypeConfiguresDefaultOptions(): void
    {
        $form = $this->factory->create(IconType::class);
        $view = $form->createView();

        self::assertSame('icon', $form->getConfig()->getType()->getInnerType()->getBlockPrefix());
        self::assertSame('Delete', $view->vars['delete_label']);
        self::assertSame('5px', $view->vars['border_radius']);
        self::assertNull($view->vars['fonts']);
    }

    public function testBuildViewNormalizesFontsIntoArray(): void
    {
        $type = new IconType();
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);

        $type->buildView($view, $form, [
            'json_url' => 'https://example.test/icons.json',
            'search_placeholder' => 'Search',
            'select_button' => 'Pick',
            'show_all_button' => 'All',
            'cancel_button' => 'Cancel',
            'no_result_found' => 'No result',
            'delete_label' => 'Delete',
            'border_radius' => '8px',
            'fonts' => 'Font Awesome 6 Free',
        ]);

        self::assertSame(['Font Awesome 6 Free'], $view->vars['fonts']);
        self::assertSame('https://example.test/icons.json', $view->vars['json_url']);
    }
}
