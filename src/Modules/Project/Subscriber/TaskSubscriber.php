<?php

namespace App\Modules\Project\Subscriber;

use App\Modules\Project\Events\TaskCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaskSubscriber implements EventSubscriberInterface
{

    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TaskCreatedEvent::class => ["onTaskCreatedEvent", 10],
        ];
    }

     public function onTaskCreatedEvent(TaskCreatedEvent $event): void
    {


    }
}