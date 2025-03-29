<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250322225334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'associate user to project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE project
            ADD createdBy INT NOT NULL, 
            ADD updatedBy INT NOT NULL
        ');

        $this->addSql('
            ALTER TABLE project
            ADD CONSTRAINT FK_project_CREATEDBY FOREIGN KEY (createdBy) REFERENCES user (id) ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE project
            ADD CONSTRAINT FK_project_UPDATEDBY FOREIGN KEY (updatedBy) REFERENCES user (id) ON DELETE CASCADE
        ');

        $this->addSql('CREATE INDEX IDX_project_CREATEDBY ON project (createdBy)');
        $this->addSql('CREATE INDEX IDX_project_UPDATEDBY ON project (updatedBy)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE project
            DROP CONSTRAINT FK_project_CREATEDBY
        ');

        $this->addSql('
            ALTER TABLE project
            DROP CONSTRAINT FK_project_UPDATEDBY
        ');

        $this->addSql('
            ALTER TABLE project
            DROP COLUMN createdBy
        ');

        $this->addSql('
            ALTER TABLE project
            DROP COLUMN updatedBy
        ');

        $this->addSql('DROP INDEX IDX_project_CREATEDBY');
        $this->addSql('DROP INDEX IDX_project_UPDATEDBY');
    }
}