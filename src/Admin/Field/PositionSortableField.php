<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class PositionSortableField implements FieldInterface
{
    public const ACTION_URL = 'actionUrl';
    public const PARENT_PROPERTY = "parentProperty";

    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        $field = (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(NumberType::class)
            ->setTemplateName("form_easy_field_position_sortable")
            ->setTemplatePath('@EasyFields/form/form-easy-field-position-sortable.html.twig')
            ->setCustomOption(self::PARENT_PROPERTY, "parent");
        return $field;
    }

    public function setParentProperty($field): static
    {
        $this->setCustomOption(self::PARENT_PROPERTY, $field);
        return $this;
    }

    public function setActionUrl($value): static
    {
        $this->setCustomOption(self::ACTION_URL, $value);
        return $this;
    }

}
