<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250712153144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding property on task for his priority';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "ALTER TABLE task ADD COLUMN priority_options VARCHAR(200) NOT NULL DEFAULT 'meduim'"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE task DROP COLUMN task_priority");
    }
}