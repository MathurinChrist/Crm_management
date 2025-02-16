<?php

namespace App\Modules\Project\Subscriber;

use App\Modules\Project\Events\TaskCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
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

    #[NoReturn] public function onTaskCreatedEvent(TaskCreatedEvent $event): void
    {

    }
}