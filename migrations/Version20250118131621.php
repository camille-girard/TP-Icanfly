<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250118131621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE booking (id SERIAL NOT NULL, customer_id INT NOT NULL, mission_id INT NOT NULL, destination VARCHAR(255) NOT NULL, seat_count INT NOT NULL, total_price INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDE9395C3F3 ON booking (customer_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEBE6CAE90 ON booking (mission_id)');
        $this->addSql('COMMENT ON COLUMN booking.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE dragon (id INT NOT NULL, seat_capacity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE falcon9 (id INT NOT NULL, crew_capacity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mission (id SERIAL NOT NULL, destination VARCHAR(255) NOT NULL, description TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, seat_price INT NOT NULL, image VARCHAR(2500) NOT NULL, duration TIME(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN mission.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE mission_spaceship (mission_id INT NOT NULL, spaceship_id INT NOT NULL, PRIMARY KEY(mission_id, spaceship_id))');
        $this->addSql('CREATE INDEX IDX_18C57BA7BE6CAE90 ON mission_spaceship (mission_id)');
        $this->addSql('CREATE INDEX IDX_18C57BA74AD9556B ON mission_spaceship (spaceship_id)');
        $this->addSql('CREATE TABLE notification (id SERIAL NOT NULL, customer_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, sent_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CA9395C3F3 ON notification (customer_id)');
        $this->addSql('CREATE TABLE payment (id SERIAL NOT NULL, mission_id INT DEFAULT NULL, total_price INT NOT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, payment_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D28840DBE6CAE90 ON payment (mission_id)');
        $this->addSql('COMMENT ON COLUMN payment.payment_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE scientific_mission (id INT NOT NULL, special_equipement VARCHAR(255) NOT NULL, objective VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE seat (id SERIAL NOT NULL, spaceship_id INT DEFAULT NULL, number INT NOT NULL, is_reserved BOOLEAN NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D5C36664AD9556B ON seat (spaceship_id)');
        $this->addSql('CREATE TABLE spaceship (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, autonomy INT NOT NULL, usage VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE starship (id INT NOT NULL, cargo_capacity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tourist_mission (id INT NOT NULL, has_guide BOOLEAN NOT NULL, activities VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX IDX_F7129A803AD8644E ON user_user (user_source)');
        $this->addSql('CREATE INDEX IDX_F7129A80233D34C1 ON user_user (user_target)');
        $this->addSql('CREATE TABLE video_streaming (id SERIAL NOT NULL, mission_id INT DEFAULT NULL, url VARCHAR(2500) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E43C65F4BE6CAE90 ON video_streaming (mission_id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE9395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dragon ADD CONSTRAINT FK_27D829B4BF396750 FOREIGN KEY (id) REFERENCES spaceship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE falcon9 ADD CONSTRAINT FK_2403FAC3BF396750 FOREIGN KEY (id) REFERENCES spaceship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission_spaceship ADD CONSTRAINT FK_18C57BA7BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission_spaceship ADD CONSTRAINT FK_18C57BA74AD9556B FOREIGN KEY (spaceship_id) REFERENCES spaceship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9395C3F3 FOREIGN KEY (customer_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DBE6CAE90 FOREIGN KEY (mission_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scientific_mission ADD CONSTRAINT FK_8896A76BF396750 FOREIGN KEY (id) REFERENCES mission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C36664AD9556B FOREIGN KEY (spaceship_id) REFERENCES seat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE starship ADD CONSTRAINT FK_C414E64ABF396750 FOREIGN KEY (id) REFERENCES spaceship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tourist_mission ADD CONSTRAINT FK_F4B59FDCBF396750 FOREIGN KEY (id) REFERENCES mission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_streaming ADD CONSTRAINT FK_E43C65F4BE6CAE90 FOREIGN KEY (mission_id) REFERENCES video_streaming (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE9395C3F3');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEBE6CAE90');
        $this->addSql('ALTER TABLE dragon DROP CONSTRAINT FK_27D829B4BF396750');
        $this->addSql('ALTER TABLE falcon9 DROP CONSTRAINT FK_2403FAC3BF396750');
        $this->addSql('ALTER TABLE mission_spaceship DROP CONSTRAINT FK_18C57BA7BE6CAE90');
        $this->addSql('ALTER TABLE mission_spaceship DROP CONSTRAINT FK_18C57BA74AD9556B');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA9395C3F3');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840DBE6CAE90');
        $this->addSql('ALTER TABLE scientific_mission DROP CONSTRAINT FK_8896A76BF396750');
        $this->addSql('ALTER TABLE seat DROP CONSTRAINT FK_3D5C36664AD9556B');
        $this->addSql('ALTER TABLE starship DROP CONSTRAINT FK_C414E64ABF396750');
        $this->addSql('ALTER TABLE tourist_mission DROP CONSTRAINT FK_F4B59FDCBF396750');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE video_streaming DROP CONSTRAINT FK_E43C65F4BE6CAE90');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE dragon');
        $this->addSql('DROP TABLE falcon9');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE mission_spaceship');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE scientific_mission');
        $this->addSql('DROP TABLE seat');
        $this->addSql('DROP TABLE spaceship');
        $this->addSql('DROP TABLE starship');
        $this->addSql('DROP TABLE tourist_mission');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE video_streaming');
    }
}
