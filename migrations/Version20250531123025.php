<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250531123025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add some properties to user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE user
            ADD createdBy INT DEFAULT NULL, 
            ADD updatedBy INT DEFAULT NULL
        ');
        $this->addSql('
    ALTER TABLE `user`
    ADD user_type VARCHAR(100) NOT NULL
');



        $this->addSql('
            ALTER TABLE user
            ADD CONSTRAINT FK_user_CREATEDBY FOREIGN KEY (createdBy) REFERENCES user (id) ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE user
            ADD CONSTRAINT FK_user_UPDATEDBY FOREIGN KEY (updatedBy) REFERENCES user (id) ON DELETE CASCADE
        ');

        $this->addSql('CREATE INDEX IDX_user_CREATEDBY ON user (createdBy)');
        $this->addSql('CREATE INDEX IDX_user_UPDATEDBY ON user (updatedBy)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `user`
            DROP COLUMN user_type
        ');

        $this->addSql('
            ALTER TABLE user
            DROP CONSTRAINT FK_user_CREATEDBY
        ');

        $this->addSql('
            ALTER TABLE user
            DROP CONSTRAINT FK_user_UPDATEDBY
        ');

        $this->addSql('
            ALTER TABLE user
            DROP COLUMN createdBy
        ');

        $this->addSql('
            ALTER TABLE user
            DROP COLUMN updatedBy
        ');

        $this->addSql('DROP INDEX IDX_user_CREATEDBY');
        $this->addSql('DROP INDEX IDX_user_UPDATEDBY');
    }
}