<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field\Configurator;

use Adeliom\EasyFieldsBundle\Admin\Field\PositionSortableField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
final class PositionSortableConfigurator implements FieldConfiguratorInterface
{
    public function __construct(
        /**
         * @readonly
         */
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return PositionSortableField::class === $field->getFieldFqcn();
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        $url = $this->adminUrlGenerator
            ->setController($context->getCrud()->getControllerFqcn())
            ->setAction('sortPositionAction')
            ->generateUrl();
        $field->setCustomOption(PositionSortableField::ACTION_URL, $url);
    }
}
