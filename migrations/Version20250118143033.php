<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118143033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seat DROP CONSTRAINT FK_3D5C36664AD9556B');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C36664AD9556B FOREIGN KEY (spaceship_id) REFERENCES spaceship (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE seat DROP CONSTRAINT fk_3d5c36664ad9556b');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT fk_3d5c36664ad9556b FOREIGN KEY (spaceship_id) REFERENCES seat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
