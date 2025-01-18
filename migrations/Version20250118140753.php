<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118140753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_streaming DROP CONSTRAINT FK_E43C65F4BE6CAE90');
        $this->addSql('ALTER TABLE video_streaming ALTER mission_id SET NOT NULL');
        $this->addSql('ALTER TABLE video_streaming ADD CONSTRAINT FK_E43C65F4BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE video_streaming DROP CONSTRAINT fk_e43c65f4be6cae90');
        $this->addSql('ALTER TABLE video_streaming ALTER mission_id DROP NOT NULL');
        $this->addSql('ALTER TABLE video_streaming ADD CONSTRAINT fk_e43c65f4be6cae90 FOREIGN KEY (mission_id) REFERENCES video_streaming (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
