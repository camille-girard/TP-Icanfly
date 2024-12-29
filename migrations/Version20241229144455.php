<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229144455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id SERIAL NOT NULL, passenger_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, booking_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total_price DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDE4502E565 ON booking (passenger_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEBE6CAE90 ON booking (mission_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E00CEDDE4C3A3BB ON booking (payment_id)');
        $this->addSql('COMMENT ON COLUMN booking.booking_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE dragon (id SERIAL NOT NULL, crew_capacity INT NOT NULL, life_support BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE falcon9 (id SERIAL NOT NULL, stage_count INT NOT NULL, reusable BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mission (id SERIAL NOT NULL, video_streaming_id INT DEFAULT NULL, spaceship_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, launch_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, seat_price DOUBLE PRECISION NOT NULL, duration INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9067F23CE369FDBD ON mission (video_streaming_id)');
        $this->addSql('CREATE INDEX IDX_9067F23C4AD9556B ON mission (spaceship_id)');
        $this->addSql('COMMENT ON COLUMN mission.launch_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN mission.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification (id SERIAL NOT NULL, mission_id INT DEFAULT NULL, passenger_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, sent_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CABE6CAE90 ON notification (mission_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA4502E565 ON notification (passenger_id)');
        $this->addSql('COMMENT ON COLUMN notification.sent_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE payment (id SERIAL NOT NULL, amount DOUBLE PRECISION NOT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, payment_method VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payment.payment_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE scientific_mission (id SERIAL NOT NULL, objectives VARCHAR(255) NOT NULL, special_equipment VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE seat (id SERIAL NOT NULL, booking_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, number INT NOT NULL, is_reserved BOOLEAN NOT NULL, price DOUBLE PRECISION NOT NULL, class VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D5C36663301C60 ON seat (booking_id)');
        $this->addSql('CREATE INDEX IDX_3D5C3666BE6CAE90 ON seat (mission_id)');
        $this->addSql('CREATE TABLE space_ship (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, capacity INT NOT NULL, manufacturer VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE starship (id SERIAL NOT NULL, autonomy INT NOT NULL, cargo_capacity DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE statistic (id SERIAL NOT NULL, mission_id INT DEFAULT NULL, value DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_649B469CBE6CAE90 ON statistic (mission_id)');
        $this->addSql('COMMENT ON COLUMN statistic.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tourist_mission (id SERIAL NOT NULL, activities VARCHAR(255) NOT NULL, has_guide BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, loyalty_points INT DEFAULT NULL, role TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".role IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE TABLE user_mission (user_id INT NOT NULL, mission_id INT NOT NULL, PRIMARY KEY(user_id, mission_id))');
        $this->addSql('CREATE INDEX IDX_C86AEC36A76ED395 ON user_mission (user_id)');
        $this->addSql('CREATE INDEX IDX_C86AEC36BE6CAE90 ON user_mission (mission_id)');
        $this->addSql('CREATE TABLE video_streaming (id SERIAL NOT NULL, url VARCHAR(2500) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN video_streaming.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN video_streaming.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE4502E565 FOREIGN KEY (passenger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CE369FDBD FOREIGN KEY (video_streaming_id) REFERENCES video_streaming (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C4AD9556B FOREIGN KEY (spaceship_id) REFERENCES space_ship (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CABE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA4502E565 FOREIGN KEY (passenger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C36663301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C3666BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE statistic ADD CONSTRAINT FK_649B469CBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE4502E565');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEBE6CAE90');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE4C3A3BB');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23CE369FDBD');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23C4AD9556B');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CABE6CAE90');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA4502E565');
        $this->addSql('ALTER TABLE seat DROP CONSTRAINT FK_3D5C36663301C60');
        $this->addSql('ALTER TABLE seat DROP CONSTRAINT FK_3D5C3666BE6CAE90');
        $this->addSql('ALTER TABLE statistic DROP CONSTRAINT FK_649B469CBE6CAE90');
        $this->addSql('ALTER TABLE user_mission DROP CONSTRAINT FK_C86AEC36A76ED395');
        $this->addSql('ALTER TABLE user_mission DROP CONSTRAINT FK_C86AEC36BE6CAE90');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE dragon');
        $this->addSql('DROP TABLE falcon9');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE scientific_mission');
        $this->addSql('DROP TABLE seat');
        $this->addSql('DROP TABLE space_ship');
        $this->addSql('DROP TABLE starship');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('DROP TABLE tourist_mission');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_mission');
        $this->addSql('DROP TABLE video_streaming');
    }
}
