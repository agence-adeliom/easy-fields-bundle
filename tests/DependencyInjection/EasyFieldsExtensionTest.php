<?php

namespace Adeliom\EasyFieldsBundle\Tests\DependencyInjection;

use Adeliom\EasyFieldsBundle\DependencyInjection\EasyFieldsExtension;
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
    }
}
