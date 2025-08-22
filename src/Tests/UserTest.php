<?php

namespace App\Tests;

use App\Security\Entity\User;
use App\Modules\Task\Entity\Task;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testFullName()
    {
        $user = new User();
        $user->setFirstName("Jane")->setLastName("Doe")->setEmail("jane@example.com")->setPassword("1234");

        $this->assertEquals("Jane Doe", $user->getFullName());
    }

    public function testAddAndRemoveTask()
    {
        $user = new User();
        $task = new Task();
        $task->setTitle("Important Task");

        $user->addTask($task);

        $this->assertTrue($user->getTasks()->contains($task));

        $user->removeTask($task);
        $this->assertFalse($user->getTasks()->contains($task));
    }

    public function testRoles()
    {
        $user = new User();
        $user->setEmail("user@example.com")->setPassword("pass");
        $user->setRoles(['ROLE_SUPER_ADMIN', 'USER']);

        $roles = $user->getRoles();

        $this->assertContains('ROLE_SUPER_ADMIN', $roles);
        $this->assertContains('USER', $roles);
    }
}



