<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250120212709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding relation between task and project entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE task DROP project_to');
        $this->addSql('ALTER TABLE task ADD project_id INT NOT NULL');

        $this->addSql('
            ALTER TABLE task 
            ADD CONSTRAINT FK_TASK_PROJECT FOREIGN KEY (project_id) REFERENCES project (id) 
            ON DELETE CASCADE
        ');

        $this->addSql('CREATE INDEX IDX_TASK_PROJECT ON task (project_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_TASK_PROJECT ON task');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_TASK_PROJECT');

        $this->addSql('ALTER TABLE task DROP COLUMN project_id');
    }
}