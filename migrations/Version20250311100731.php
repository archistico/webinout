<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311100731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creazione movimenti ricorrenti';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movimento_ricorrente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, descrizione VARCHAR(255) NOT NULL, importo DOUBLE PRECISION NOT NULL, inizio DATETIME NOT NULL, fine DATETIME DEFAULT NULL, frequenza VARCHAR(255) NOT NULL, giorno_pagamento INTEGER NOT NULL, attivo BOOLEAN NOT NULL, CONSTRAINT FK_7AC996123397707A FOREIGN KEY (categoria_id) REFERENCES micro (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7AC996123397707A ON movimento_ricorrente (categoria_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE movimento_ricorrente');
    }
}
