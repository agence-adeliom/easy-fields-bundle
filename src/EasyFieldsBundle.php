<?php

namespace Adeliom\EasyFieldsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Adeliom\EasyFieldsBundle\DependencyInjection\EasyFieldsExtension;

class EasyFieldsBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EasyFieldsExtension();
    }
}
