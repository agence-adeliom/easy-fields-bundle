<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class FormTypeField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null, $formType = TextType::class): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType($formType)
        ;
    }
}
