<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250621144704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'this migration file is for the user assigned to task';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE task_user (
        task_id INT NOT NULL,
        user_id INT NOT NULL,
        PRIMARY KEY(task_id, user_id)
    )');
        $this->addSql('CREATE INDEX IDX_TASK_USER_TASK ON task_user (task_id)');
        $this->addSql('CREATE INDEX IDX_TASK_USER_USER ON task_user (user_id)');

        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_TASK_USER_TASK FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_TASK_USER_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE task_user');
    }
}
