<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Adeliom\EasyFieldsBundle\Form\IconType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class IconField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null, $fieldsConfig = []): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@EasyFields/crud/field/icon.html.twig')
            ->setFormType(IconType::class)
            ->addCssClass('field-easy-field-iconpicker')
            ->setDefaultColumns('col-md-8 col-xxl-7')
            ->hideOnIndex()
        ;
    }

    public function setRequired(bool $isRequired): self
    {
        $this->setFormTypeOption('required', $isRequired);
        return $this;
    }

    public function setJsonUrl(string $jsonUrl): self
    {
        $this->setFormTypeOption('json_url', $jsonUrl);
        return $this;
    }

    public function setSelectButtonLabel(string $label): self
    {
        $this->setFormTypeOption('select_button', $label);
        return $this;
    }

    public function setCancelButtonLabel(string $label): self
    {
        $this->setFormTypeOption('cancel_button', $label);
        return $this;
    }

    public function setShowAllButtonLabel(string $label): self
    {
        $this->setFormTypeOption('show_all_button', $label);
        return $this;
    }

    public function setSearchPlaceholder(string $label): self
    {
        $this->setFormTypeOption('search_placeholder', $label);
        return $this;
    }

    public function setNotResultMessage(string $message): self
    {
        $this->setFormTypeOption('no_result_found', $message);
        return $this;
    }

    /**
     * @param string|mixed[] $fonts
     */
    public function setFonts(string|array $fonts = []): self
    {
        $this->setFormTypeOption('fonts', $fonts);
        return $this;
    }
}
