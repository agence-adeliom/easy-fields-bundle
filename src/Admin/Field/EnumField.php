<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use Adeliom\EasyCommonBundle\Helper\Enum;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class EnumField implements FieldInterface
{
    use FieldTrait;

    /**
     * @var string
     */
    public const OPTION_ENUM = 'enum';

    /**
     * @var string
     */
    public const OPTION_RENDER_AS_BADGES = 'renderAsBadges';

    /**
     * @var string
     */
    public const OPTION_RENDER_EXPANDED = 'renderExpanded';

    /**
     * @var string
     */
    public const OPTION_ALLOW_MULTIPLE_CHOICES = 'allowMultipleChoices';

    /**
     * @var string
     */
    public const OPTION_WIDGET = 'widget';

    /**
     * @var string
     */
    public const OPTION_ESCAPE_HTML_CONTENTS = 'escapeHtml';

    /**
     * @var string[]
     */
    public const VALID_BADGE_TYPES = ['success', 'warning', 'danger', 'info', 'primary', 'secondary', 'light', 'dark'];

    /**
     * @var string
     */
    public const WIDGET_AUTOCOMPLETE = 'autocomplete';

    /**
     * @var string
     */
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
            ->setFormType(ChoiceType::class)
            ->addCssClass('field-select')
            ->setDefaultColumns('') // this is set dynamically in the field configurator
            ->setCustomOption(self::OPTION_ENUM, null)
            ->setCustomOption(self::OPTION_RENDER_AS_BADGES, null)
            ->setCustomOption(self::OPTION_RENDER_EXPANDED, false)
            ->setCustomOption(self::OPTION_WIDGET, self::WIDGET_NATIVE)
            ->setCustomOption(self::OPTION_ESCAPE_HTML_CONTENTS, true)
            ->setCustomOption(self::OPTION_ALLOW_MULTIPLE_CHOICES, false)
        ;
    }

    /**
     * Given enum must follow the same format used in Symfony Forms:.
     */
    public function setEnum(string $enumFcqn): self
    {
        if (!class_exists($enumFcqn) || !is_a($enumFcqn, Enum::class, true)) {
            throw new InvalidConfigurationException(sprintf('Enum class must be a valid class extending %s. "%s" given.', Enum::class, $enumFcqn));
        }

        $this->setCustomOption(self::OPTION_ENUM, $enumFcqn);

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

    public function allowMultipleChoices(bool $allowMultipleChoices = true): self
    {
        $this->setCustomOption(self::OPTION_ALLOW_MULTIPLE_CHOICES, $allowMultipleChoices);

        return $this;
    }

    public function escapeHtml(bool $escape = true): self
    {
        $this->setCustomOption(self::OPTION_ESCAPE_HTML_CONTENTS, $escape);

        return $this;
    }
}
