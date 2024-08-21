<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240820184123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add tags table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE post_tags (blog_post_id VARCHAR(36) NOT NULL, tag_id VARCHAR(36) NOT NULL, PRIMARY KEY(blog_post_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_A6E9F32DA77FBEAF ON post_tags (blog_post_id)');
        $this->addSql('CREATE INDEX IDX_A6E9F32DBAD26311 ON post_tags (tag_id)');
        $this->addSql('CREATE TABLE tags (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN tags.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32DA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32DBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE post_tags DROP CONSTRAINT FK_A6E9F32DA77FBEAF');
        $this->addSql('ALTER TABLE post_tags DROP CONSTRAINT FK_A6E9F32DBAD26311');
        $this->addSql('DROP TABLE post_tags');
        $this->addSql('DROP TABLE tags');
    }
}
