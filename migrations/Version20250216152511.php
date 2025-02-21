<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250216152511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create comments table and add relation between comments and task';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE comments (
            id INT AUTO_INCREMENT NOT NULL, 
            task_id INT NOT NULL, 
            content TEXT NOT NULL, 
            created_at DATETIME NOT NULL, 
            INDEX IDX_TASK_COMMENT (task_id), 
            PRIMARY KEY(id), 
            CONSTRAINT FK_TASK_COMMENT FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP FOREIGN KEY FK_TASK_COMMENT');
        $this->addSql('DROP INDEX IDX_TASK_COMMENT');
        $this->addSql('DROP TABLE comments');

    }
}