<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241229145214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add inheritance table space_ship and mission';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE dragon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE falcon9_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE scientific_mission_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE starship_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tourist_mission_id_seq CASCADE');
        $this->addSql('ALTER TABLE dragon ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE dragon ADD CONSTRAINT FK_27D829B4BF396750 FOREIGN KEY (id) REFERENCES space_ship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE falcon9 ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE falcon9 ADD CONSTRAINT FK_2403FAC3BF396750 FOREIGN KEY (id) REFERENCES space_ship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD mission_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE scientific_mission ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE scientific_mission ADD CONSTRAINT FK_8896A76BF396750 FOREIGN KEY (id) REFERENCES mission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE space_ship ADD spaceship_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE starship ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE starship ADD CONSTRAINT FK_C414E64ABF396750 FOREIGN KEY (id) REFERENCES space_ship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tourist_mission ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE tourist_mission ADD CONSTRAINT FK_F4B59FDCBF396750 FOREIGN KEY (id) REFERENCES mission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE dragon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE falcon9_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE scientific_mission_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE starship_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tourist_mission_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE falcon9 DROP CONSTRAINT FK_2403FAC3BF396750');
        $this->addSql('CREATE SEQUENCE falcon9_id_seq');
        $this->addSql('SELECT setval(\'falcon9_id_seq\', (SELECT MAX(id) FROM falcon9))');
        $this->addSql('ALTER TABLE falcon9 ALTER id SET DEFAULT nextval(\'falcon9_id_seq\')');
        $this->addSql('ALTER TABLE space_ship DROP spaceship_type');
        $this->addSql('ALTER TABLE dragon DROP CONSTRAINT FK_27D829B4BF396750');
        $this->addSql('CREATE SEQUENCE dragon_id_seq');
        $this->addSql('SELECT setval(\'dragon_id_seq\', (SELECT MAX(id) FROM dragon))');
        $this->addSql('ALTER TABLE dragon ALTER id SET DEFAULT nextval(\'dragon_id_seq\')');
        $this->addSql('ALTER TABLE mission DROP mission_type');
        $this->addSql('ALTER TABLE scientific_mission DROP CONSTRAINT FK_8896A76BF396750');
        $this->addSql('CREATE SEQUENCE scientific_mission_id_seq');
        $this->addSql('SELECT setval(\'scientific_mission_id_seq\', (SELECT MAX(id) FROM scientific_mission))');
        $this->addSql('ALTER TABLE scientific_mission ALTER id SET DEFAULT nextval(\'scientific_mission_id_seq\')');
        $this->addSql('ALTER TABLE starship DROP CONSTRAINT FK_C414E64ABF396750');
        $this->addSql('CREATE SEQUENCE starship_id_seq');
        $this->addSql('SELECT setval(\'starship_id_seq\', (SELECT MAX(id) FROM starship))');
        $this->addSql('ALTER TABLE starship ALTER id SET DEFAULT nextval(\'starship_id_seq\')');
        $this->addSql('ALTER TABLE tourist_mission DROP CONSTRAINT FK_F4B59FDCBF396750');
        $this->addSql('CREATE SEQUENCE tourist_mission_id_seq');
        $this->addSql('SELECT setval(\'tourist_mission_id_seq\', (SELECT MAX(id) FROM tourist_mission))');
        $this->addSql('ALTER TABLE tourist_mission ALTER id SET DEFAULT nextval(\'tourist_mission_id_seq\')');
    }
}
