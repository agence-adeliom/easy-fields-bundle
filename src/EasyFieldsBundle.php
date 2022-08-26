<?php

namespace Adeliom\EasyFieldsBundle;

use Adeliom\EasyFieldsBundle\DependencyInjection\EasyFieldsExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EasyFieldsBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new EasyFieldsExtension();
    }
}
