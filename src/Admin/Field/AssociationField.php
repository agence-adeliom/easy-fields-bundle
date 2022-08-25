<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class AssociationField implements FieldInterface
{
    use FieldTrait;

    /**
     * @var string
     */
    public const OPTION_AUTOCOMPLETE = 'autocomplete';

    /**
     * @var string
     */
    public const OPTION_CRUD_CONTROLLER = 'crudControllerFqcn';

    /**
     * @var string
     */
    public const OPTION_QUERY_BUILDER_CALLABLE = 'queryBuilderCallable';

    /** @internal this option is intended for internal use only
     * @var string */
    public const OPTION_RELATED_URL = 'relatedUrl';

    /** @internal this option is intended for internal use only
     * @var string */
    public const OPTION_DOCTRINE_ASSOCIATION_TYPE = 'associationType';

    /**
     * @var string
     */
    public const OPTION_ALLOW_ADD = 'allow_add';

    /**
     * @var string
     */
    public const OPTION_BUTTON_ADD_LABEL = 'button_add_label';

    /**
     * @var string
     */
    public const OPTION_BUTTON_ADD_ICON = 'button_add_icon';

    /**
     * @var string
     */
    public const OPTION_LIST_SELECTOR = 'list_selector';

    /**
     * @var string
     */
    public const OPTION_LIST_BUTTON_LABEL = 'list_button_label';

    /**
     * @var string
     */
    public const OPTION_LIST_BUTTON_ICON = 'list_button_icon';

    /**
     * @var string
     */
    public const OPTION_LIST_BUTTON_CANCEL_LABEL = 'list_button_cancel_label';

    /**
     * @var string
     */
    public const OPTION_LIST_BUTTON_VALIDATE_LABEL = 'list_button_validate_label';

    /**
     * @var string
     */
    public const OPTION_LIST_SHOW_FILTER = 'list_show_filter';

    /**
     * @var string
     */
    public const OPTION_LIST_SHOW_SEARCH = 'list_show_search';

    /**
     * @var string
     */
    public const OPTION_LIST_DISPLAY_COLUMNS = 'list_display_columns';

    /**
     * @var string
     */
    public const OPTION_LIST_FILTERS = 'list_filters';

    /** @internal this option is intended for internal use only
     * @var string */
    public const PARAM_AUTOCOMPLETE_CONTEXT = 'autocompleteContext';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@EasyFields/crud/field/association.html.twig')
            ->setFormType(EntityType::class)
            ->addCssClass('field-association')
            ->addJsFiles('bundles/easyfields/form-type-association-new-ajax.js')
            ->addJsFiles('bundles/easyfields/form-type-association-list.js')
            ->addCssFiles('bundles/easyfields/form-type-association.css')

            ->setCustomOption(self::OPTION_AUTOCOMPLETE, false)
            ->setCustomOption(self::OPTION_CRUD_CONTROLLER, null)
            ->setCustomOption(self::OPTION_RELATED_URL, null)
            ->setCustomOption(self::OPTION_DOCTRINE_ASSOCIATION_TYPE, null)
            ->setCustomOption(self::OPTION_ALLOW_ADD, false)
            ->setCustomOption(self::OPTION_LIST_SELECTOR, false)
            ;
    }

    public static function getSettableOptions(): array
    {
        return [
            self::OPTION_BUTTON_ADD_LABEL,
            self::OPTION_BUTTON_ADD_ICON,
            self::OPTION_ALLOW_ADD,
            self::OPTION_LIST_SELECTOR,
            self::OPTION_LIST_BUTTON_LABEL,
            self::OPTION_LIST_BUTTON_ICON,
            self::OPTION_LIST_BUTTON_CANCEL_LABEL,
            self::OPTION_LIST_BUTTON_VALIDATE_LABEL,
            self::OPTION_LIST_SHOW_FILTER,
            self::OPTION_LIST_SHOW_SEARCH,
            self::OPTION_LIST_FILTERS,
            self::OPTION_LIST_DISPLAY_COLUMNS,
        ];
    }

    public function allowAdd(bool $allow = true): self
    {
        $this->setCustomOption(self::OPTION_ALLOW_ADD, $allow);

        return $this;
    }

    public function autocomplete(): self
    {
        $this->setCustomOption(self::OPTION_AUTOCOMPLETE, true);

        return $this;
    }

    public function setCrudController(string $crudControllerFqcn): self
    {
        $this->setCustomOption(self::OPTION_CRUD_CONTROLLER, $crudControllerFqcn);

        return $this;
    }

    public function setButtonAddLabel(string $label): self
    {
        $this->setCustomOption(self::OPTION_BUTTON_ADD_LABEL, $label);

        return $this;
    }

    public function setButtonAddIcon(string $icon): self
    {
        $this->setCustomOption(self::OPTION_BUTTON_ADD_ICON, $icon);

        return $this;
    }

    public function listSelector(bool $add = true): self
    {
        $this->setCustomOption(self::OPTION_LIST_SELECTOR, $add);

        return $this;
    }

    public function listButtonIcon(string $icon): self
    {
        $this->setCustomOption(self::OPTION_LIST_BUTTON_ICON, $icon);

        return $this;
    }

    public function listButtonLabel(string $label): self
    {
        $this->setCustomOption(self::OPTION_LIST_BUTTON_LABEL, $label);

        return $this;
    }

    public function listButtonCancelLabel(string $label): self
    {
        $this->setCustomOption(self::OPTION_LIST_BUTTON_CANCEL_LABEL, $label);

        return $this;
    }

    public function listButtonValidateLabel(string $label): self
    {
        $this->setCustomOption(self::OPTION_LIST_BUTTON_VALIDATE_LABEL, $label);

        return $this;
    }

    public function listShowFilter(bool $show = true): self
    {
        $this->setCustomOption(self::OPTION_LIST_SHOW_FILTER, $show);

        return $this;
    }

    public function listShowSearch(bool $show = true): self
    {
        $this->setCustomOption(self::OPTION_LIST_SHOW_SEARCH, $show);

        return $this;
    }

    public function listFilters(array $filters = []): self
    {
        $this->setCustomOption(self::OPTION_LIST_FILTERS, $filters);

        return $this;
    }

    public function listDisplayColumns($columns = 1, $separator = '-'): self
    {
        if (!\is_array($columns)) {
            $columns = [$columns];
        }

        $this->setCustomOption(self::OPTION_LIST_DISPLAY_COLUMNS, ['columns' => $columns, 'separator' => $separator]);

        return $this;
    }

    public function setQueryBuilder(\Closure $queryBuilderCallable): self
    {
        $this->setCustomOption(self::OPTION_QUERY_BUILDER_CALLABLE, $queryBuilderCallable);

        return $this;
    }
}
