<?php

namespace Adeliom\EasyFieldsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceMaskType extends AbstractType
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $sanitizedMap = [];
        $allFieldNames = [];
        foreach ($options['map'] as $value => $fieldNames) {
            if (is_iterable($fieldNames)) {
                foreach ($fieldNames as $fieldName) {
                    $sanitizedFieldName = str_replace(['__', '.'], ['____', '__'], (string) $fieldName);
                    $sanitizedMap[$value][] = $sanitizedFieldName;
                    $allFieldNames[] = $sanitizedFieldName;
                }
            }
        }

        $view->vars['all_fields'] = array_values(array_unique($allFieldNames));
        $view->vars['map'] = $sanitizedMap;

        $options['expanded'] = false;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'map' => [],
        ]);
        $resolver->setAllowedTypes('map', 'array');
    }

    /**
     * @phpstan-return class-string<FormTypeInterface>
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'choice_field_mask';
    }
}
