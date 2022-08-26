<?php

namespace Adeliom\EasyFieldsBundle\Form\Extension;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CrudAutocompleteType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityTypeExtension extends AbstractTypeExtension
{
    public function __construct(protected AdminUrlGenerator $adminUrlGenerator)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            AssociationField::OPTION_ALLOW_ADD => false,
            AssociationField::OPTION_BUTTON_ADD_LABEL => 'action.add_new_item',
            AssociationField::OPTION_BUTTON_ADD_ICON => 'fa-plus',
            AssociationField::OPTION_LIST_SELECTOR => false,
            AssociationField::OPTION_CRUD_CONTROLLER => null,
            AssociationField::OPTION_LIST_BUTTON_LABEL => 'action.list_items',
            AssociationField::OPTION_LIST_BUTTON_ICON => 'fa-list',
            AssociationField::OPTION_LIST_BUTTON_CANCEL_LABEL => 'action.list.cancel',
            AssociationField::OPTION_LIST_BUTTON_VALIDATE_LABEL => 'action.list.validate',
            AssociationField::OPTION_LIST_SHOW_FILTER => false,
            AssociationField::OPTION_LIST_SHOW_SEARCH => false,
            AssociationField::OPTION_LIST_FILTERS => null,
            AssociationField::OPTION_LIST_DISPLAY_COLUMNS => null,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $settableOptions = AssociationField::getSettableOptions();
        foreach ($settableOptions as $option) {
            $view->vars[$option] = $options[$option];
        }

        if (isset($options[AssociationField::OPTION_ALLOW_ADD]) && $options[AssociationField::OPTION_ALLOW_ADD] && !empty($options[AssociationField::OPTION_CRUD_CONTROLLER])) {
            $ajaxEndpointUrl = $this->adminUrlGenerator
                ->setController($options[AssociationField::OPTION_CRUD_CONTROLLER])
                ->setAction('new')
                ->generateUrl();
            $view->vars['attr']['data-ea-ajax-new-endpoint-url'] = $ajaxEndpointUrl;
        }

        // dump($options[AssociationField::OPTION_LIST_SELECTOR]); exit;

        if (isset($options[AssociationField::OPTION_LIST_SELECTOR]) && $options[AssociationField::OPTION_LIST_SELECTOR] && !empty($options[AssociationField::OPTION_CRUD_CONTROLLER])) {
            $ajaxEndpointUrl = $this->adminUrlGenerator
                ->setController($options[AssociationField::OPTION_CRUD_CONTROLLER])
                ->setAction('index')
                ->generateUrl();
            $view->vars['attr']['data-ea-ajax-index-url'] = $ajaxEndpointUrl;
        }

        /*if(isset($view->vars['attr']["data-ea-widget"]) && $view->vars['attr']["data-ea-widget"] == "ea-autocomplete"){
            if(!isset($view->vars['attr']['data-ea-autocomplete-endpoint-url'])){
                $view->vars['attr']['data-ea-autocomplete-endpoint-url'] = '/';
            }
        }*/
    }

    /**
     * @return class-string[]
     */
    public static function getExtendedTypes(): array
    {
        return [EntityType::class, CrudAutocompleteType::class];
    }
}
