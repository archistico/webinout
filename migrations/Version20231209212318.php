<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231209212318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movimento (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, tipo_id INTEGER NOT NULL, data DATETIME NOT NULL, importo DOUBLE PRECISION DEFAULT NULL, note CLOB DEFAULT NULL, CONSTRAINT FK_5BE0E9153397707A FOREIGN KEY (categoria_id) REFERENCES micro (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5BE0E915A9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_pagamento (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5BE0E9153397707A ON movimento (categoria_id)');
        $this->addSql('CREATE INDEX IDX_5BE0E915A9276E6C ON movimento (tipo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE movimento');
    }
}
