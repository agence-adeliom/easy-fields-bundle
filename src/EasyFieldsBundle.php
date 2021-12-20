<?php

namespace Adeliom\EasyFieldsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Adeliom\EasyFieldsBundle\DependencyInjection\EasyFieldsExtension;

class EasyFieldsBundle extends Bundle
{
    /**
     * @return ExtensionInterface|null The container extension
     */
    public function getContainerExtension()
    {
        return new EasyFieldsExtension();
    }
}
