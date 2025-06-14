<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604155614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promos DROP FOREIGN KEY FK_31D1F705AABEFE2C');
        $this->addSql('DROP INDEX IDX_31D1F705AABEFE2C ON promos');
        $this->addSql('ALTER TABLE promos CHANGE id_produit_id id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705F7384557 FOREIGN KEY (id_produit) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_31D1F705F7384557 ON promos (id_produit)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promos DROP FOREIGN KEY FK_31D1F705F7384557');
        $this->addSql('DROP INDEX IDX_31D1F705F7384557 ON promos');
        $this->addSql('ALTER TABLE promos CHANGE id_produit id_produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produits (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_31D1F705AABEFE2C ON promos (id_produit_id)');
    }
}
