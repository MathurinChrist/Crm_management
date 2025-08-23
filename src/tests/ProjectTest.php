<?php

namespace App\tests;

use App\Modules\Project\Entity\Project;
use App\Modules\Task\Entity\Task;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testProjectInitialization(): void
    {
        $project = new Project();
        $this->assertNull($project->getId());
        $this->assertEmpty($project->getTask());
        $this->assertSame(0, $project->getTasksNumber());
    }

    public function testAddAndRemoveTask(): void
    {
        $project = new Project();
        $project->setName("My Project");
        $project->setDescription("Test description");

        $task = new Task();

        $project->addTask($task);

        $this->assertCount(1, $project->getTask());
        $this->assertSame($project, $task->getProject());

        $project->removeTask($task);

        $this->assertCount(0, $project->getTask());
    }

    public function testSettersAndGetters(): void
    {
        $project = new Project();
        $project->setName("My Project");
        $project->setDescription("Test description");
        $project->setStatus("todo");

        $this->assertSame("My Project", $project->getName());
        $this->assertSame("Test description", $project->getDescription());
        $this->assertSame("todo", $project->getStatus());
    }
}


