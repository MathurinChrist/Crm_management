<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250308173926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'migration for entity user creation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            first_name VARCHAR(255) NOT NULL,
            gender VARCHAR(255) NOT NULL default \'M\',
            email VARCHAR(180) NOT NULL ,
            INDEX IDX_USER_EMAIL (email),
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_USER_EMAIL');
        $this->addSql('DROP TABLE user');
    }
}
