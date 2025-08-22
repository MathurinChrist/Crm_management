<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250731175458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des champs pour le système de réinitialisation de mot de passe';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD reset_token_expires_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP reset_token');
        $this->addSql('ALTER TABLE user DROP reset_token_expires_at');
    }
}
