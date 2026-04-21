<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260421191148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE progetto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fk_progetto_tipologia_id INTEGER NOT NULL, descrizione VARCHAR(255) NOT NULL, CONSTRAINT FK_A96B9EAFEAABCF8C FOREIGN KEY (fk_progetto_tipologia_id) REFERENCES progetto_tipologia (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A96B9EAFEAABCF8C ON progetto (fk_progetto_tipologia_id)');
        $this->addSql('CREATE TABLE progetto_azione (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fk_progetto_id INTEGER NOT NULL, descrizione VARCHAR(255) NOT NULL, inizio DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , fine DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_B33641BCE8316CB4 FOREIGN KEY (fk_progetto_id) REFERENCES progetto (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B33641BCE8316CB4 ON progetto_azione (fk_progetto_id)');
        $this->addSql('CREATE TABLE progetto_tipologia (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descrizione VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE progetto');
        $this->addSql('DROP TABLE progetto_azione');
        $this->addSql('DROP TABLE progetto_tipologia');
    }
}
