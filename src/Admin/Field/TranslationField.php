<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;


use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class TranslationField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null, $fieldsConfig = []): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@EasyFields/crud/field/translation.html.twig')
            ->setFormType(TranslationsType::class)
            ->hideOnIndex()
            ->hideOnDetail()
            ->setFormTypeOptions(
                [
                    'required' => true,
                    'fields' => $fieldsConfig,
                    'constraints' => [
                        new Assert\Valid()
                    ],
                ]
            );
    }

    public function setRequired(bool $isRequired): self
    {
        $this->setFormTypeOption('required', $isRequired);
        return $this;
    }
}
