<?php

namespace Adeliom\EasyFieldsBundle\Tests\Form\Extension;

use Adeliom\EasyFieldsBundle\Form\Extension\CollectionTypeExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\Extension\CollectionTypeExtension::class)]
final class CollectionTypeExtensionTest extends TestCase
{
    public function testExtensionRegistersOnCollectionTypeAndExposesViewVars(): void
    {
        $extension = new CollectionTypeExtension();
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve([
            'sortable' => true,
            'button_add_label' => 'Add item',
            'button_delete_label' => 'Delete item',
        ]);

        $view = new FormView();
        $extension->buildView($view, $this->createMock(FormInterface::class), $options);

        self::assertSame([CollectionType::class], CollectionTypeExtension::getExtendedTypes());
        self::assertTrue($view->vars['sortable']);
        self::assertSame('Add item', $view->vars['button_add_label']);
        self::assertSame('Delete item', $view->vars['button_delete_label']);
    }
}
