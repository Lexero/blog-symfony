<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231013134136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Password and confirmation_code can be null in users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER INDEX idx_ba5ae01df675f31b RENAME TO IDX_885DBAFAF675F31B');
        $this->addSql('ALTER TABLE users ALTER confirmation_code DROP NOT NULL');
        $this->addSql('ALTER TABLE users ALTER password DROP NOT NULL');
        $this->addSql('ALTER INDEX uniq_8d93d649e7927c74 RENAME TO UNIQ_1483A5E9E7927C74');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ALTER confirmation_code SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER password SET NOT NULL');
        $this->addSql('ALTER INDEX uniq_1483a5e9e7927c74 RENAME TO uniq_8d93d649e7927c74');
        $this->addSql('ALTER INDEX idx_885dbafaf675f31b RENAME TO idx_ba5ae01df675f31b');
    }
}
