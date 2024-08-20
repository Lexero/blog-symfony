<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240817103957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change int id to uuid for posts and comments table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE posts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comments_id_seq CASCADE');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT IF EXISTS FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE comments ALTER id TYPE VARCHAR(36)');
        $this->addSql('ALTER TABLE comments ALTER post_id TYPE VARCHAR(36)');
        $this->addSql('ALTER TABLE posts ALTER id TYPE VARCHAR(36)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ALTER title DROP NOT NULL');
        $this->addSql('ALTER TABLE posts ALTER body DROP NOT NULL');
        $this->addSql('ALTER TABLE posts ALTER slug DROP NOT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF675F31B FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT fk_ba5ae01df675f31b');
        $this->addSql('CREATE INDEX IDX_885DBAFA8B8E8428 ON posts (created_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE posts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT IF EXISTS FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE posts ALTER id TYPE INT');
        $this->addSql('ALTER TABLE comments ALTER id TYPE INT');
        $this->addSql('ALTER TABLE comments ALTER post_id TYPE INT');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ALTER title SET NOT NULL');
        $this->addSql('ALTER TABLE posts ALTER body SET NOT NULL');
        $this->addSql('ALTER TABLE posts ALTER slug SET NOT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT fk_ba5ae01df675f31b FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX IDX_885DBAFA8B8E8428');
    }
}
