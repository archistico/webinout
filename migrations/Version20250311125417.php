<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311125417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('DROP TABLE ripetuto');
        //$this->addSql('DROP TABLE ripetuto_conferma');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE TABLE ripetuto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, tipo_id INTEGER NOT NULL, inizio DATE NOT NULL, fine DATE NOT NULL, rinnovo VARCHAR(255) NOT NULL COLLATE "BINARY", giorno INTEGER DEFAULT NULL, mese INTEGER DEFAULT NULL, note VARCHAR(255) DEFAULT NULL COLLATE "BINARY", CONSTRAINT FK_EFB0E8053397707A FOREIGN KEY (categoria_id) REFERENCES micro (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EFB0E805A9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_pagamento (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        //$this->addSql('CREATE INDEX IDX_EFB0E805A9276E6C ON ripetuto (tipo_id)');
        //$this->addSql('CREATE INDEX IDX_EFB0E8053397707A ON ripetuto (categoria_id)');
        //$this->addSql('CREATE TABLE ripetuto_conferma (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ripetuto_id_id INTEGER NOT NULL, movimento_id_id INTEGER DEFAULT NULL, data DATE NOT NULL, CONSTRAINT FK_205A8FDD2D9B661 FOREIGN KEY (ripetuto_id_id) REFERENCES ripetuto (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_205A8FDD3535C424 FOREIGN KEY (movimento_id_id) REFERENCES movimento (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        //$this->addSql('CREATE INDEX IDX_205A8FDD3535C424 ON ripetuto_conferma (movimento_id_id)');
        //$this->addSql('CREATE INDEX IDX_205A8FDD2D9B661 ON ripetuto_conferma (ripetuto_id_id)');
    }
}
