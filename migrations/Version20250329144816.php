<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250329144816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Associate a comment osted to user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
                ALTER TABLE comment
                ADD createdBy INT NOT NULL, 
                ADD updatedBy INT NOT NULL
            ');

        $this->addSql('
                ALTER TABLE comment
                ADD CONSTRAINT FK_comment_CREATEDBY FOREIGN KEY (createdBy) REFERENCES user (id) ON DELETE CASCADE
            ');

        $this->addSql('
                ALTER TABLE comment
                ADD CONSTRAINT FK_comment_UPDATEDBY FOREIGN KEY (updatedBy) REFERENCES user (id) ON DELETE CASCADE
            ');

        $this->addSql('CREATE INDEX IDX_comment_CREATEDBY ON comment (createdBy)');
        $this->addSql('CREATE INDEX IDX_comment_UPDATEDBY ON comment (updatedBy)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE comment
            DROP CONSTRAINT FK_comment_CREATEDBY
        ');

        $this->addSql('
            ALTER TABLE comment
            DROP CONSTRAINT FK_comment_UPDATEDBY
        ');

        $this->addSql('
            ALTER TABLE comment
            DROP COLUMN createdBy
        ');

        $this->addSql('
            ALTER TABLE comment
            DROP COLUMN updatedBy
        ');

        $this->addSql('DROP INDEX IDX_comment_CREATEDBY');
        $this->addSql('DROP INDEX IDX_comment_UPDATEDBY');
    }
}