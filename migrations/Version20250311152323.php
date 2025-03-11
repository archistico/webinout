<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311152323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__movimento_ricorrente AS SELECT id, categoria_id, descrizione, importo, inizio, fine, frequenza, giorno_pagamento, attivo FROM movimento_ricorrente');
        $this->addSql('DROP TABLE movimento_ricorrente');
        $this->addSql('CREATE TABLE movimento_ricorrente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, tipo_id INTEGER NOT NULL, descrizione VARCHAR(255) NOT NULL, importo DOUBLE PRECISION NOT NULL, inizio DATETIME NOT NULL, fine DATETIME DEFAULT NULL, frequenza VARCHAR(255) NOT NULL, giorno_pagamento INTEGER NOT NULL, attivo BOOLEAN NOT NULL, CONSTRAINT FK_7AC996123397707A FOREIGN KEY (categoria_id) REFERENCES micro (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7AC99612A9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_pagamento (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movimento_ricorrente (id, categoria_id, descrizione, importo, inizio, fine, frequenza, giorno_pagamento, attivo) SELECT id, categoria_id, descrizione, importo, inizio, fine, frequenza, giorno_pagamento, attivo FROM __temp__movimento_ricorrente');
        $this->addSql('DROP TABLE __temp__movimento_ricorrente');
        $this->addSql('CREATE INDEX IDX_7AC996123397707A ON movimento_ricorrente (categoria_id)');
        $this->addSql('CREATE INDEX IDX_7AC99612A9276E6C ON movimento_ricorrente (tipo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__movimento_ricorrente AS SELECT id, categoria_id, descrizione, importo, inizio, fine, frequenza, giorno_pagamento, attivo FROM movimento_ricorrente');
        $this->addSql('DROP TABLE movimento_ricorrente');
        $this->addSql('CREATE TABLE movimento_ricorrente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, descrizione VARCHAR(255) NOT NULL, importo DOUBLE PRECISION NOT NULL, inizio DATETIME NOT NULL, fine DATETIME DEFAULT NULL, frequenza VARCHAR(255) NOT NULL, giorno_pagamento INTEGER NOT NULL, attivo BOOLEAN NOT NULL, CONSTRAINT FK_7AC996123397707A FOREIGN KEY (categoria_id) REFERENCES micro (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movimento_ricorrente (id, categoria_id, descrizione, importo, inizio, fine, frequenza, giorno_pagamento, attivo) SELECT id, categoria_id, descrizione, importo, inizio, fine, frequenza, giorno_pagamento, attivo FROM __temp__movimento_ricorrente');
        $this->addSql('DROP TABLE __temp__movimento_ricorrente');
        $this->addSql('CREATE INDEX IDX_7AC996123397707A ON movimento_ricorrente (categoria_id)');
    }
}
