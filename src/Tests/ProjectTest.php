<?php

namespace App\Tests;

use App\Modules\Project\Entity\Project;
use App\Modules\Task\Entity\Task;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testAddTask()
    {
        $project = new Project();
        $project->setName("New Project")->setStatus("todo");

        $task = new Task();
        $task->setTitle("Task 1");

        $project->addTask($task);

        $this->assertTrue($project->getTask()->contains($task));
        $this->assertEquals($project, $task->getProject());
    }

    public function testRemoveTask()
    {
        $project = new Project();
        $task = new Task();
        $task->setTitle("Task X");
        $project->addTask($task);

        $project->removeTask($task);

        $this->assertFalse($project->getTask()->contains($task));
    }
}


