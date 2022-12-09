<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209125222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_writing_message_id INT DEFAULT NULL, user_receiving_message_id INT DEFAULT NULL, user_advise_message_id INT DEFAULT NULL, plant_informed_by_message_id INT DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, INDEX IDX_B6BD307FEA69A405 (user_writing_message_id), INDEX IDX_B6BD307F7A8620CA (user_receiving_message_id), INDEX IDX_B6BD307FC2973003 (user_advise_message_id), INDEX IDX_B6BD307F25C88CC6 (plant_informed_by_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, user_giving_note_id INT DEFAULT NULL, user_receiving_note_id INT DEFAULT NULL, valeur INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, INDEX IDX_CFBDFA14FA0D3187 (user_giving_note_id), INDEX IDX_CFBDFA14307E389D (user_receiving_note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plante (id INT AUTO_INCREMENT NOT NULL, user_owning_plant_id INT DEFAULT NULL, user_keeping_plant_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, periode_arrosage LONGTEXT DEFAULT NULL, famille VARCHAR(255) DEFAULT NULL, position_gps VARCHAR(255) DEFAULT NULL, INDEX IDX_517A6947F936D54F (user_owning_plant_id), INDEX IDX_517A6947603F3A28 (user_keeping_plant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FEA69A405 FOREIGN KEY (user_writing_message_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7A8620CA FOREIGN KEY (user_receiving_message_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC2973003 FOREIGN KEY (user_advise_message_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F25C88CC6 FOREIGN KEY (plant_informed_by_message_id) REFERENCES plante (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FA0D3187 FOREIGN KEY (user_giving_note_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14307E389D FOREIGN KEY (user_receiving_note_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE plante ADD CONSTRAINT FK_517A6947F936D54F FOREIGN KEY (user_owning_plant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE plante ADD CONSTRAINT FK_517A6947603F3A28 FOREIGN KEY (user_keeping_plant_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FEA69A405');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7A8620CA');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC2973003');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F25C88CC6');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14FA0D3187');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14307E389D');
        $this->addSql('ALTER TABLE plante DROP FOREIGN KEY FK_517A6947F936D54F');
        $this->addSql('ALTER TABLE plante DROP FOREIGN KEY FK_517A6947603F3A28');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE plante');
    }
}
