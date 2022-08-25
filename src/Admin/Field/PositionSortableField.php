<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class PositionSortableField implements FieldInterface
{
    use FieldTrait;
    /**
     * @var string
     */
    public const ACTION_URL = 'actionUrl';

    /**
     * @var string
     */
    public const PARENT_PROPERTY = 'parentProperty';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(NumberType::class)
            ->setTemplateName('form_easy_field_position_sortable')
            ->setTemplatePath('@EasyFields/form/form-easy-field-position-sortable.html.twig')
            ->setCustomOption(self::PARENT_PROPERTY, 'parent');
    }

    /**
     * @return $this
     */
    public function setParentProperty($field)
    {
        $this->setCustomOption(self::PARENT_PROPERTY, $field);

        return $this;
    }

    /**
     * @return $this
     */
    public function setActionUrl($value)
    {
        $this->setCustomOption(self::ACTION_URL, $value);

        return $this;
    }
}
