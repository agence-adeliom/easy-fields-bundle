<?php

namespace Adeliom\EasyFieldsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        // this defines the available options and their default values when
        // they are not configured explicitly when using the form type
        $resolver->setDefaults([
            'json_url' => 'https://cdn.cbd.int/@furcan/iconpicker@1.5.0/dist/iconpicker-1.5.0.json',
            'search_placeholder' => 'Search Icon',
            'select_button' => 'Select Icon',
            'show_all_button' => 'Show All',
            'cancel_button' => 'Cancel',
            'no_result_found' => 'No results found.',
            'border_radius' => '5px',
            'fonts' => null,
        ]);

        // optionally you can also restrict the options type or types (to get
        // automatic type validation and useful error messages for end users)
        $resolver->setAllowedTypes('json_url', ['string']);
        $resolver->setAllowedTypes('select_button', ['string']);
        $resolver->setAllowedTypes('search_placeholder', ['string']);
        $resolver->setAllowedTypes('show_all_button', ['string']);
        $resolver->setAllowedTypes('cancel_button', ['string']);
        $resolver->setAllowedTypes('no_result_found', ['string']);
        $resolver->setAllowedTypes('border_radius', ['string']);
        $resolver->setAllowedTypes('fonts', ['null', 'string', 'array']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['json_url'] = $options['json_url'];
        $view->vars['select_button'] = $options['select_button'];
        $view->vars['search_placeholder'] = $options['search_placeholder'];
        $view->vars['show_all_button'] = $options['show_all_button'];
        $view->vars['cancel_button'] = $options['cancel_button'];
        $view->vars['no_result_found'] = $options['no_result_found'];
        $view->vars['border_radius'] = $options['border_radius'];
        $view->vars['fonts'] = null;
        if (!empty($options['fonts'])) {
            $view->vars['fonts'] = is_string($options['fonts']) ? [$options['fonts']] : $options['fonts'];
        }
    }

    /**
     * @phpstan-return class-string<FormTypeInterface>
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'icon';
    }
}
