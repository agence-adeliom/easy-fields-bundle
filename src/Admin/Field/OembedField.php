<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use Adeliom\EasyFieldsBundle\Form\OembedType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class OembedField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null, $fieldsConfig = []): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@EasyFields/crud/field/oembed.html.twig')
            ->setFormType(OembedType::class)
            ->hideOnIndex()
        ;
    }

    public function setRequired(bool $isRequired): self
    {
        $this->setFormTypeOption('required', $isRequired);

        return $this;
    }
}
