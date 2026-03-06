<?php

namespace Adeliom\EasyFieldsBundle\Tests\Form;

use PHPUnit\Framework\Attributes\CoversClass;
use Adeliom\EasyFieldsBundle\Form\SortableCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

#[CoversClass(\Adeliom\EasyFieldsBundle\Form\SortableCollectionType::class)]
final class SortableCollectionTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        return [
            new PreloadedExtension([new SortableCollectionType()], []),
        ];
    }

    public function testTypeExposesCustomViewVariables(): void
    {
        $form = $this->factory->create(SortableCollectionType::class, ['first', 'second'], [
            'entry_type' => TextType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'allow_drag' => true,
            'hide_title' => true,
        ]);

        $view = $form->createView();

        self::assertTrue($view->vars['allow_drag']);
        self::assertTrue($view->vars['allow_add']);
        self::assertTrue($view->vars['allow_delete']);
        self::assertTrue($view->vars['hide_title']);
        self::assertArrayHasKey('prototype', $view->vars);
        self::assertContains('sortable_collection_entry', $view[0]->vars['block_prefixes']);
    }
}
