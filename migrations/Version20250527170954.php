<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527170954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes_produits ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes_produits ADD CONSTRAINT FK_D58023F0D0C07AFF FOREIGN KEY (promo_id) REFERENCES promos (id)');
        $this->addSql('CREATE INDEX IDX_D58023F0D0C07AFF ON commandes_produits (promo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes_produits DROP FOREIGN KEY FK_D58023F0D0C07AFF');
        $this->addSql('DROP INDEX IDX_D58023F0D0C07AFF ON commandes_produits');
        $this->addSql('ALTER TABLE commandes_produits DROP promo_id');
    }
}
