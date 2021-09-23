<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;


use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class ChoiceMaskField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_CHOICES = 'choices';
    public const OPTION_MAP = 'map';
    public const OPTION_RENDER_AS_BADGES = 'renderAsBadges';
    public const OPTION_RENDER_EXPANDED = 'renderExpanded';
    public const OPTION_WIDGET = 'widget';
    public const OPTION_ESCAPE_HTML_CONTENTS = 'escapeHtml';

    public const VALID_BADGE_TYPES = ['success', 'warning', 'danger', 'info', 'primary', 'secondary', 'light', 'dark'];

    public const WIDGET_AUTOCOMPLETE = 'autocomplete';
    public const WIDGET_NATIVE = 'native';

    /**
     * @param string|false|null $label
     */
    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplateName('crud/field/choice')
            ->setFormType(ChoiceMaskType::class)
            ->addCssClass('field-select')
            ->setDefaultColumns('') // this is set dynamically in the field configurator
            ->setCustomOption(self::OPTION_CHOICES, null)
            ->setCustomOption(self::OPTION_MAP, [])
            ->setCustomOption(self::OPTION_RENDER_AS_BADGES, null)
            ->setCustomOption(self::OPTION_RENDER_EXPANDED, false)
            ->setCustomOption(self::OPTION_WIDGET, self::WIDGET_NATIVE)
            ->setCustomOption(self::OPTION_ESCAPE_HTML_CONTENTS, true);
    }

    /**
     * Given choices must follow the same format used in Symfony Forms:
     * ['Label visible to users' => 'submitted_value', ...].
     *
     * In addition to an array, you can use a PHP callback, which is passed the instance
     * of the current entity (it can be null) and the FieldDto as the second argument:
     * ->setChoices(fn () => ['foo' => 1, 'bar' => 2])
     * ->setChoices(fn (?MyEntity $foo) => $foo->someField()->getChoices())
     * ->setChoices(fn (?MyEntity $foo, FieldDto $field) => ...)
     */
    public function setChoices($choiceGenerator): self
    {
        if (!\is_array($choiceGenerator) && !\is_callable($choiceGenerator)) {
            throw new \InvalidArgumentException(sprintf('The argument of the "%s" method must be an array or a closure ("%s" given).', __METHOD__, \gettype($choiceGenerator)));
        }

        $this->setCustomOption(self::OPTION_CHOICES, $choiceGenerator);

        return $this;
    }

    /**
     * Given choices must follow the same format used in Symfony Forms:
     * ['submitted_value' => ['visible_field_name'], ...].
     *
     * In addition to an array, you can use a PHP callback, which is passed the instance
     * of the current entity (it can be null) and the FieldDto as the second argument:
     * ->setMap(fn () => ['foo' => ['bar']])
     * ->setMap(fn (?MyEntity $foo) => $foo->someField()->getChoices())
     * ->setMap(fn (?MyEntity $foo, FieldDto $field) => ...)
     */
    public function setMap($mapGenerator): self
    {
        if (!\is_array($mapGenerator) && !\is_callable($mapGenerator)) {
            throw new \InvalidArgumentException(sprintf('The argument of the "%s" method must be an array or a closure ("%s" given).', __METHOD__, \gettype($mapGenerator)));
        }

        $this->setCustomOption(self::OPTION_MAP, $mapGenerator);

        return $this;
    }

    /**
     * Possible values of $badgeSelector:
     *   * true: all values are displayed as 'secondary' badges
     *   * false: no badges are displayed; values are displayed as regular text
     *   * array: [$fieldValue => $badgeType, ...] (e.g. ['foo' => 'primary', 7 => 'warning', 'cancelled' => 'danger'])
     *   * callable: function(FieldDto $field): string { return '...' }
     *     (e.g. function(FieldDto $field) { return $field->getValue() < 10 ? 'warning' : 'primary'; }).
     *
     * Possible badge types: 'success', 'warning', 'danger', 'info', 'primary', 'secondary', 'light', 'dark'
     */
    public function renderAsBadges($badgeSelector = true): self
    {
        if (!\is_bool($badgeSelector) && !\is_array($badgeSelector) && !\is_callable($badgeSelector)) {
            throw new \InvalidArgumentException(sprintf('The argument of the "%s" method must be a boolean, an array or a closure ("%s" given).', __METHOD__, \gettype($badgeSelector)));
        }

        if (\is_array($badgeSelector)) {
            foreach ($badgeSelector as $fieldValue => $badgeType) {
                if (!\in_array($badgeType, self::VALID_BADGE_TYPES, true)) {
                    throw new \InvalidArgumentException(sprintf('The values of the array passed to the "%s" method must be one of the following valid badge types: "%s" ("%s" given).', __METHOD__, implode(', ', self::VALID_BADGE_TYPES), $badgeType));
                }
            }
        }

        $this->setCustomOption(self::OPTION_RENDER_AS_BADGES, $badgeSelector);

        return $this;
    }

    public function renderExpanded(bool $expanded = true): self
    {
        $this->setCustomOption(self::OPTION_RENDER_EXPANDED, $expanded);

        return $this;
    }

    public function escapeHtml(bool $escape = true): self
    {
        $this->setCustomOption(self::OPTION_ESCAPE_HTML_CONTENTS, $escape);

        return $this;
    }
}
