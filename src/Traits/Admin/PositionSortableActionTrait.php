<?php

namespace Adeliom\EasyFieldsBundle\Traits\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait PositionSortableActionTrait
{
    public function sortPositionAction(AdminContext $context): Response
    {
        $requestContent = json_decode($context->getRequest()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        [$leftPrimaryKeyValue, $leftParentProperty] = $requestContent['l'] ? explode(':', (string) $requestContent['l']) : [null, null];
        [$rightPrimaryKeyValue, $rightParentProperty] = $requestContent['r'] ? explode(':', (string) $requestContent['r']) : [null, null];
        [$primaryKeyValue, $parentProperty] = $requestContent['c'] ? explode(':', (string) $requestContent['c']) : [null, null];
        $leftEntity = null;
        $entity = null;
        $rightEntity = null;
        $entity = null;

        if ($leftPrimaryKeyValue !== null && $leftPrimaryKeyValue !== '' && $leftPrimaryKeyValue !== '0' && ($leftParentProperty !== null && $leftParentProperty !== '' && $leftParentProperty !== '0')) {
            $leftEntity = $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->find($leftPrimaryKeyValue);
        }

        if ($rightPrimaryKeyValue !== null && $rightPrimaryKeyValue !== '' && $rightPrimaryKeyValue !== '0' && ($rightParentProperty !== null && $rightParentProperty !== '' && $rightParentProperty !== '0')) {
            $rightEntity = $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->find($rightPrimaryKeyValue);
        }

        if ($primaryKeyValue !== null && $primaryKeyValue !== '' && $primaryKeyValue !== '0' && ($parentProperty !== null && $parentProperty !== '' && $parentProperty !== '0')) {
            $entity = $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->find($primaryKeyValue);
        }

        $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->recover();

        if (!empty($leftEntity)) {
            try {
                if ($leftEntity->{'get'.ucfirst((string) $parentProperty)}() !== $entity->{'get'.ucfirst((string) $parentProperty)}()) {
                    $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->persistAsFirstChildOf($entity, $leftEntity);
                } else {
                    $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->persistAsNextSiblingOf($entity, $leftEntity);
                }
            } catch (Exception) {
                $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->persistAsFirstChildOf($entity, $leftEntity);
            }
        } elseif (!empty($rightEntity)) {
            try {
                $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->persistAsPrevSiblingOf($entity, $rightEntity);
            } catch (Exception) {
                $this->managerRegistry->getRepository($context->getEntity()->getFqcn())->persistAsNextSiblingOf($rightEntity, $entity);
            }
        }

        $this->managerRegistry->getManagerForClass($context->getEntity()->getFqcn())->flush();

        return new JsonResponse(['redirectTo' => null]);
    }
}
