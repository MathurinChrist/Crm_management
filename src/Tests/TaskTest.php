<?php

namespace App\Tests;

use App\Modules\Task\Entity\Task;
use App\Modules\Project\Entity\Project;
use App\Security\Entity\User;
use App\Modules\checkListTask\Entity\ChecklistItem;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testCreateTask()
    {
        $task = new Task();
        $task->setTitle('My Task')
            ->setContext('Dev context')
            ->setStatus('todo')
            ->setPriorityOptions('high')
            ->setDescription('A simple description');

        $this->assertEquals('My Task', $task->getTitle());
        $this->assertEquals('Dev context', $task->getContext());
        $this->assertEquals('todo', $task->getStatus());
        $this->assertEquals('high', $task->getPriorityOptions());
        $this->assertEquals('A simple description', $task->getDescription());
    }

    public function testAssignUser()
    {
        $task = new Task();
        $user = new User();
        $user->setFirstName("John")->setLastName("Doe")->setEmail("john@example.com")->setPassword("1234");

        $task->addAssignedUser($user);

        $this->assertTrue($task->getAssignedUsers()->contains($user));
    }

    public function testChecklistAssociation()
    {
        $task = new Task();
        $item = new ChecklistItem();
        $item->setTitle("Check DB");

        $task->addChecklistItem($item);

        $this->assertTrue($task->getChecklist()->contains($item));
        $this->assertEquals($task, $item->getTask());
    }

    public function testProjectAssociation()
    {
        $project = new Project();
        $project->setName("Test Project");

        $task = new Task();
        $task->setTitle("Task A");
        $task->setProject($project);

        $this->assertEquals($project, $task->getProject());
    }
}

