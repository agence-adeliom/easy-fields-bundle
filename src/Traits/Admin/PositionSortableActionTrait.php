<?php

namespace Adeliom\EasyFieldsBundle\Traits\Admin;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait PositionSortableActionTrait {
    public function __construct(protected ManagerRegistry $managerRegistry)
    {
    }

    public function sortPositionAction(AdminContext $context): Response
    {
        $requestContent = json_decode($context->getRequest()->getContent(), true);
        [$leftPrimaryKeyValue, $leftParentProperty] = $requestContent['l'] ? explode(':', $requestContent['l']) : [null, null];
        [$rightPrimaryKeyValue, $rightParentProperty] = $requestContent['r'] ? explode(':', $requestContent['r']) : [null, null];
        [$primaryKeyValue, $parentProperty] = $requestContent['c'] ? explode(':', $requestContent['c']) : [null, null];

        $leftEntity = $rightEntity = $entity = null;

        if ( !empty($leftPrimaryKeyValue) &&  !empty($leftParentProperty) ) {

            $leftEntity = $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->find($leftPrimaryKeyValue);

        }

        if ( !empty($rightPrimaryKeyValue) &&  !empty($rightParentProperty) ) {

            $rightEntity = $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->find($rightPrimaryKeyValue);

        }

        if ( !empty($primaryKeyValue) &&  !empty($parentProperty) ) {

            $entity = $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->find($primaryKeyValue);

        }

        $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->recover();

        if (!empty($leftEntity)) {
            try {
                if ($leftEntity->{'get' . ucFirst($parentProperty)}() !== $entity->{'get' . ucFirst($parentProperty)}()) {
                    $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->persistAsFirstChildOf($entity, $leftEntity);
                } else {
                    $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->persistAsNextSiblingOf($entity, $leftEntity);
                }
            } catch (Exception) {
                $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->persistAsFirstChildOf($entity, $leftEntity);
            }
        } else if (!empty($rightEntity)) {
            try {
                $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->persistAsPrevSiblingOf($entity, $rightEntity);
            } catch (Exception) {
                $this->managerRegistry->getRepository( $context->getEntity()->getFqcn() )->persistAsNextSiblingOf($rightEntity, $entity);
            }
        }

        $this->managerRegistry->getManagerForClass( $context->getEntity()->getFqcn() )->flush();

        return new JsonResponse(['redirectTo' => null]);
    }

}
