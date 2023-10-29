<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029162652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'tabelle macro, meso, micro';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE macro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, invio BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE meso (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, padre_id INTEGER NOT NULL, nome VARCHAR(255) NOT NULL, invio BOOLEAN NOT NULL, CONSTRAINT FK_7F0BAFF5613CEC58 FOREIGN KEY (padre_id) REFERENCES macro (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7F0BAFF5613CEC58 ON meso (padre_id)');
        $this->addSql('CREATE TABLE micro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, padre_id INTEGER NOT NULL, nome VARCHAR(255) NOT NULL, invio BOOLEAN NOT NULL, CONSTRAINT FK_6B3BEEAC613CEC58 FOREIGN KEY (padre_id) REFERENCES meso (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6B3BEEAC613CEC58 ON micro (padre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE macro');
        $this->addSql('DROP TABLE meso');
        $this->addSql('DROP TABLE micro');
    }
}
