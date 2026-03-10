<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests\DependencyInjection;

use Adeliom\EasyFieldsBundle\DependencyInjection\EasyFieldsExtension;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class EasyFieldsExtensionTest extends TestCase
{
    public function testExtensionLoadsServiceDefinitions(): void
    {
        $container = new ContainerBuilder();
        $extension = new EasyFieldsExtension();

        $extension->load([], $container);

        self::assertSame('easy_fields', $extension->getAlias());
        self::assertTrue($container->hasDefinition('Adeliom\\EasyFieldsBundle\\Form\\SortableCollectionType'));
        self::assertTrue($container->hasDefinition('Adeliom\\EasyFieldsBundle\\Form\\ChoiceMaskType'));
        self::assertTrue($container->hasDefinition('Adeliom\\EasyFieldsBundle\\Twig\\OembedExtension'));
        self::assertTrue($container->hasAlias(AdminUrlGeneratorInterface::class));
        self::assertSame(AdminUrlGenerator::class, (string) $container->getAlias(AdminUrlGeneratorInterface::class));
    }
}
