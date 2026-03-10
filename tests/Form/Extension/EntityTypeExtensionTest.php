<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\Form\Extension;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Form\Extension\EntityTypeExtension;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CrudAutocompleteType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGeneratorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\Extension\EntityTypeExtension::class)]
final class EntityTypeExtensionTest extends TestCase
{
    public function testExtensionRegistersSupportedTypes(): void
    {
        self::assertSame([EntityType::class, CrudAutocompleteType::class], EntityTypeExtension::getExtendedTypes());
    }

    public function testBuildViewAddsAjaxAttributesWhenConfigured(): void
    {
        $adminUrlGenerator = $this->createMock(AdminUrlGeneratorInterface::class);
        $adminUrlGenerator->method('setController')->willReturnSelf();

        $calls = [];
        $adminUrlGenerator->method('setAction')
            ->willReturnCallback(function (string $action) use ($adminUrlGenerator, &$calls) {
                $calls[] = $action;

                return $adminUrlGenerator;
            });

        $adminUrlGenerator->method('generateUrl')
            ->willReturnCallback(function () use (&$calls): string {
                return match (end($calls)) {
                    'new' => '/admin/new-item',
                    'index' => '/admin/list-items',
                    default => throw new \LogicException('Unexpected action.'),
                };
            });

        $extension = new EntityTypeExtension($adminUrlGenerator);
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        $options = $resolver->resolve([
            AssociationField::OPTION_ALLOW_ADD => true,
            AssociationField::OPTION_LIST_SELECTOR => true,
            AssociationField::OPTION_CRUD_CONTROLLER => 'App\\Controller\\Admin\\ItemCrudController',
        ]);

        $view = new FormView();
        $view->vars['attr'] = [];
        $extension->buildView($view, $this->createMock(FormInterface::class), $options);

        self::assertSame('/admin/new-item', $view->vars['attr']['data-ea-ajax-new-endpoint-url']);
        self::assertSame('/admin/list-items', $view->vars['attr']['data-ea-ajax-index-url']);
        self::assertTrue($view->vars[AssociationField::OPTION_ALLOW_ADD]);
        self::assertTrue($view->vars[AssociationField::OPTION_LIST_SELECTOR]);
    }
}
