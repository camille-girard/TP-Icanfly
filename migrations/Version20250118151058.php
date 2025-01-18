<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118151058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA9395C3F3');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476ca9395c3f3');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT fk_bf5476ca9395c3f3 FOREIGN KEY (customer_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
