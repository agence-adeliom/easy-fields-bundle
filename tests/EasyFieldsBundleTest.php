<?php

declare(strict_types=1);

namespace Adeliom\EasyFieldsBundle\Tests;

use Adeliom\EasyFieldsBundle\DependencyInjection\EasyFieldsExtension;
use Adeliom\EasyFieldsBundle\EasyFieldsBundle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasyFieldsBundle\EasyFieldsBundle::class)]
final class EasyFieldsBundleTest extends TestCase
{
    public function testBundleCreatesItsContainerExtension(): void
    {
        self::assertInstanceOf(EasyFieldsExtension::class, (new EasyFieldsBundle())->getContainerExtension());
    }
}
