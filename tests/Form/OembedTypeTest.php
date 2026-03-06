<?php

namespace Adeliom\EasyFieldsBundle\Tests\Form;

use Adeliom\EasyFieldsBundle\Form\OembedType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\OembedType::class)]
final class OembedTypeTest extends TestCase
{
    public function testTypeUsesUrlParentAndCustomPrefix(): void
    {
        $type = new OembedType();

        self::assertSame(UrlType::class, $type->getParent());
        self::assertSame('oembed', $type->getBlockPrefix());
    }
}
