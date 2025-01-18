<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118150424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840DBE6CAE90');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840dbe6cae90');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840dbe6cae90 FOREIGN KEY (mission_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
