<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118141508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_mission (user_id INT NOT NULL, mission_id INT NOT NULL, PRIMARY KEY(user_id, mission_id))');
        $this->addSql('CREATE INDEX IDX_C86AEC36A76ED395 ON user_mission (user_id)');
        $this->addSql('CREATE INDEX IDX_C86AEC36BE6CAE90 ON user_mission (mission_id)');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT fk_f7129a803ad8644e');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT fk_f7129a80233d34c1');
        $this->addSql('DROP TABLE user_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX idx_f7129a80233d34c1 ON user_user (user_target)');
        $this->addSql('CREATE INDEX idx_f7129a803ad8644e ON user_user (user_source)');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT fk_f7129a803ad8644e FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT fk_f7129a80233d34c1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_mission DROP CONSTRAINT FK_C86AEC36A76ED395');
        $this->addSql('ALTER TABLE user_mission DROP CONSTRAINT FK_C86AEC36BE6CAE90');
        $this->addSql('DROP TABLE user_mission');
    }
}
