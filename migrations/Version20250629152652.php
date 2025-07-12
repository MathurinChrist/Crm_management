<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250629152652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table checklist_item liée à la tâche';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE checklist_item (
            id INT AUTO_INCREMENT NOT NULL,
            task_id INT NOT NULL,
            text VARCHAR(255) NOT NULL,
            completed TINYINT(1) NOT NULL,
            INDEX IDX_CHECKLIST_TASK_ID (task_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE checklist_item 
            ADD CONSTRAINT FK_CHECKLIST_TASK_ID 
            FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE checklist_item');
    }
}