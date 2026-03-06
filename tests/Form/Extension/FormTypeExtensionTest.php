<?php

namespace Adeliom\EasyFieldsBundle\Tests\Form\Extension;

use Adeliom\EasyFieldsBundle\Form\Extension\FormTypeExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\Extension\FormTypeExtension::class)]
final class FormTypeExtensionTest extends TestCase
{
    public function testExtensionAddsColumnSizeViewVariable(): void
    {
        $extension = new FormTypeExtension();
        $resolver = new OptionsResolver();

        $extension->configureOptions($resolver);
        $options = $resolver->resolve([
            'column_size' => 'col-md-6',
        ]);

        $view = new FormView();
        $extension->buildView($view, $this->createMock(FormInterface::class), $options);

        self::assertSame([FormType::class], FormTypeExtension::getExtendedTypes());
        self::assertSame('col-md-6', $view->vars['column_size']);
    }
}
