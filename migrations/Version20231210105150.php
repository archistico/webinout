<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210105150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allegato (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, movimento_id INTEGER NOT NULL, nomefile VARCHAR(255) NOT NULL, CONSTRAINT FK_622BC057531A0E2D FOREIGN KEY (movimento_id) REFERENCES movimento (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_622BC057531A0E2D ON allegato (movimento_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE allegato');
    }
}
