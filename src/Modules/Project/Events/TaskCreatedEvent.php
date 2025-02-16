<?php

namespace App\Modules\Project\Events;

use App\Modules\Task\Entity\Task;
use Symfony\Contracts\EventDispatcher\Event;

class TaskCreatedEvent extends Event
{
    private ?Task $task = null;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

}