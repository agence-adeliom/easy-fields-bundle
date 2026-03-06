<?php

namespace Adeliom\EasyFieldsBundle\Tests\Form\Extension;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Form\Extension\EntityTypeExtension;
use Psr\Cache\CacheItemPoolInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CrudAutocompleteType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Router\AdminRouteGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Registry\DashboardControllerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\Extension\EntityTypeExtension::class)]
final class EntityTypeExtensionTest extends TestCase
{
    public function testExtensionRegistersSupportedTypes(): void
    {
        self::assertSame([EntityType::class, CrudAutocompleteType::class], EntityTypeExtension::getExtendedTypes());
    }

    public function testBuildViewAddsAjaxAttributesWhenConfigured(): void
    {
        $cacheDir = sys_get_temp_dir().'/easy-fields-tests/'.bin2hex(random_bytes(6));
        mkdir($cacheDir.'/easyadmin', 0777, true);
        file_put_contents(
            $cacheDir.'/easyadmin/routes-dashboard.php',
            "<?php\n\nreturn ['admin' => 'App\\\\Controller\\\\Admin\\\\DashboardController::index'];\n"
        );

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $cache = $this->createMock(CacheItemPoolInterface::class);
        $generatedUrls = [];
        $urlGenerator
            ->expects(self::exactly(2))
            ->method('generate')
            ->willReturnCallback(static function (string $route, array $parameters, int $referenceType) use (&$generatedUrls): string {
                $generatedUrls[] = [$route, $parameters, $referenceType];

                return match ($route) {
                    'admin_item_new' => '/admin/new-item',
                    'admin_item_index' => '/admin/list-items',
                    default => throw new \LogicException(sprintf('Unexpected route "%s".', $route)),
                };
            });

        $adminUrlGenerator = new AdminUrlGenerator(
            new AdminContextProvider(new RequestStack()),
            $urlGenerator,
            new DashboardControllerRegistry(
                $cacheDir,
                ['App\\Controller\\Admin\\DashboardController' => 'dashboard_context'],
                ['dashboard_context' => 'App\\Controller\\Admin\\DashboardController']
            ),
            new class() implements AdminRouteGeneratorInterface {
                public function generateAll(): \Symfony\Component\Routing\RouteCollection
                {
                    return new \Symfony\Component\Routing\RouteCollection();
                }

                public function findRouteName(string $dashboardFqcn, string $crudControllerFqcn, string $actionName): ?string
                {
                    return match ($actionName) {
                        'new' => 'admin_item_new',
                        'index' => 'admin_item_index',
                        default => null,
                    };
                }

                public function usesPrettyUrls(): bool
                {
                    return true;
                }
            },
            $cache
        );

        register_shutdown_function(static function () use ($cacheDir): void {
            @unlink($cacheDir.'/easyadmin/routes-dashboard.php');
            @rmdir($cacheDir.'/easyadmin');
            @rmdir($cacheDir);
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
        self::assertSame([
            ['admin_item_new', [], UrlGeneratorInterface::ABSOLUTE_URL],
            ['admin_item_index', [], UrlGeneratorInterface::ABSOLUTE_URL],
        ], $generatedUrls);
    }
}
