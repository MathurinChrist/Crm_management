<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250223161755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add timestampable to comment, project, tak entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment ADD created_at DATETIME NOT NULL ');
        $this->addSql('ALTER TABLE comment ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE task ADD created_at DATETIME NOT NULL ');
        $this->addSql('ALTER TABLE task ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE project ADD created_at DATETIME NOT NULL ');
        $this->addSql('ALTER TABLE project ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        //todo refactor for removeving those fieds
        /*$this->addSql('DROP table comment');
        $this->addSql('DROP table task');
        $this->addSql('DROP table project'); */

        $this->addSql('ALTER TABLE comment DROP created_at');
        $this->addSql('ALTER TABLE comment DROP updated_at');
        $this->addSql('ALTER TABLE project DROP created_at');
        $this->addSql('ALTER TABLE project DROP updated_at');
        $this->addSql('ALTER TABLE task DROP created_at');
        $this->addSql('ALTER TABLE task DROP updated_at');
    }
}
