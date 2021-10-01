<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field\Configurator;

use Adeliom\EasyFieldsBundle\Admin\Field\EnumField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use function Symfony\Component\String\u;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
final class EnumConfigurator implements FieldConfiguratorInterface
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return EnumField::class === $field->getFieldFqcn();
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        $isExpanded = $field->getCustomOption(EnumField::OPTION_RENDER_EXPANDED);
        $isMultiple = $field->getCustomOption(EnumField::OPTION_ALLOW_MULTIPLE_CHOICES);

        $choices = $this->getChoices($field->getCustomOption(EnumField::OPTION_ENUM), $entityDto, $field);

        if (empty($choices)) {
            throw new \InvalidArgumentException(sprintf('The "%s" choice field must define its possible choices using the setEnum() method.', $field->getProperty()));
        }

        $field->setFormTypeOptionIfNotSet('choices', $choices);
        $field->setFormTypeOptionIfNotSet('expanded', $isExpanded);
        $field->setFormTypeOptionIfNotSet('multiple', $isMultiple);


        $field->setCustomOption(EnumField::OPTION_WIDGET, EnumField::WIDGET_NATIVE);

        $field->setFormTypeOptionIfNotSet('placeholder', '');

        // the value of this form option must be a string to properly propagate it as an HTML attribute value
        $field->setFormTypeOption('attr.data-ea-autocomplete-render-items-as-html', $field->getCustomOption(EnumField::OPTION_ESCAPE_HTML_CONTENTS) ? 'false' : 'true');

        $fieldValue = $field->getValue();
        $isIndexOrDetail = \in_array($context->getCrud()->getCurrentPage(), [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true);
        if (null === $fieldValue || !$isIndexOrDetail) {
            return;
        }

        $badgeSelector = $field->getCustomOption(EnumField::OPTION_RENDER_AS_BADGES);
        $isRenderedAsBadge = null !== $badgeSelector && false !== $badgeSelector;

        $translationParameters = $context->getI18n()->getTranslationParameters();
        $translationDomain = $context->getI18n()->getTranslationDomain();
        $selectedChoices = [];
        $flippedChoices = array_flip($choices);
        // $value is a scalar for single selections and an array for multiple selections
        foreach (array_values((array) $fieldValue) as $selectedValue) {
            if (null !== $selectedChoice = $flippedChoices[$selectedValue] ?? null) {
                $choiceValue = $this->translator->trans($selectedChoice, $translationParameters, $translationDomain);
                $selectedChoices[] = $isRenderedAsBadge
                    ? sprintf('<span class="%s">%s</span>', $this->getBadgeCssClass($badgeSelector, $selectedValue, $field), $choiceValue)
                    : $choiceValue;
            }
        }
        $field->setFormattedValue(implode($isRenderedAsBadge ? '' : ', ', $selectedChoices));
    }

    private function getChoices($enum, EntityDto $entity, FieldDto $field): array
    {
        if (null === $enum) {
            return [];
        }

        $choicesEnum = $enum::toArray();
        $choices = [];
        foreach ($choicesEnum as $k => $v){
            $choices[sprintf("easy.enum.%s.%s", $field->getProperty() , $v)] = $v;
        }

        return $choices;
    }

    private function getBadgeCssClass($badgeSelector, $value, FieldDto $field): string
    {
        $commonBadgeCssClass = 'badge';

        if (true === $badgeSelector) {
            $badgeType = 'badge-secondary';
        } elseif (\is_array($badgeSelector)) {
            $badgeType = $badgeSelector[$value] ?? 'badge-secondary';
        } elseif (\is_callable($badgeSelector)) {
            $badgeType = $badgeSelector($value, $field);
            if (!\in_array($badgeType, EnumField::VALID_BADGE_TYPES, true)) {
                throw new \RuntimeException(sprintf('The value returned by the callable passed to the "renderAsBadges()" method must be one of the following valid badge types: "%s" ("%s" given).', implode(', ', ChoiceField::VALID_BADGE_TYPES), $badgeType));
            }
        }

        $badgeTypeCssClass = empty($badgeType) ? '' : u($badgeType)->ensureStart('badge-')->toString();

        return $commonBadgeCssClass.' '.$badgeTypeCssClass;
    }
}
