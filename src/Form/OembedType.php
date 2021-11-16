<?php

namespace Adeliom\EasyFieldsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormTypeInterface;

class OembedType extends AbstractType
{
    /**
     * @phpstan-return class-string<FormTypeInterface>
     */
    public function getParent(): string
    {
        return UrlType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'oembed';
    }
}
