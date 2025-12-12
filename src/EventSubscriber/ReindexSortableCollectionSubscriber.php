<?php

namespace Adeliom\EasyFieldsBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ReindexSortableCollectionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function onSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        if (!is_array($data)) {
            return;
        }
        //re-index collection data without any array index
        $event->setData(array_values($data));
    }
}
