<?php

namespace Adeliom\EasyFieldsBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['sortable'] = $options['sortable'];
        $view->vars['button_add_label'] = $options['button_add_label'];
        $view->vars['button_delete_label'] = $options['button_delete_label'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'sortable' => false,
            'button_add_label' => 'form.collection.add',
            'button_delete_label' => 'form.collection.delete',
        ]);
    }

    /**
     * Return the class of the type being extended.
     *
     * @return array<class-string<CollectionType>>
     */
    public static function getExtendedTypes(): iterable
    {
        // return FormType::class to modify (nearly) every field in the system
        return [CollectionType::class];
    }
}
