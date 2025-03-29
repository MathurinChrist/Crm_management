<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250322133533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoutez ici une description de la migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE task
            ADD createdBy INT NOT NULL, 
            ADD updatedBy INT NOT NULL
        ');

        $this->addSql('
            ALTER TABLE task
            ADD CONSTRAINT FK_TASK_CREATEDBY FOREIGN KEY (createdBy) REFERENCES user (id) ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE task
            ADD CONSTRAINT FK_TASK_UPDATEDBY FOREIGN KEY (updatedBy) REFERENCES user (id) ON DELETE CASCADE
        ');

        $this->addSql('CREATE INDEX IDX_TASK_CREATEDBY ON task (createdBy)');
        $this->addSql('CREATE INDEX IDX_TASK_UPDATEDBY ON task (updatedBy)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE task
            DROP CONSTRAINT FK_TASK_CREATEDBY
        ');

        $this->addSql('
            ALTER TABLE task
            DROP CONSTRAINT FK_TASK_UPDATEDBY
        ');

        $this->addSql('
            ALTER TABLE task
            DROP COLUMN createdBy
        ');

        $this->addSql('
            ALTER TABLE task
            DROP COLUMN updatedBy
        ');

        $this->addSql('DROP INDEX IDX_TASK_CREATEDBY');
        $this->addSql('DROP INDEX IDX_TASK_UPDATEDBY');
    }
}