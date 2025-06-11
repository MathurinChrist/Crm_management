<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250610131944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'this migration will add a status property on  project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD status VARCHAR(255) DEFAULT "todo"');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP status');
    }
}