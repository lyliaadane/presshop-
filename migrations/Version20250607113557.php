<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607113557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateurs ADD password VARCHAR(255) NOT NULL, ADD roles JSON NOT NULL, DROP mdp, CHANGE nom_utilisateur nom_utilisateur VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315ED37CC8AC ON utilisateurs (nom_utilisateur)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_497B315ED37CC8AC ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs ADD mdp VARCHAR(255) DEFAULT NULL, DROP password, DROP roles, CHANGE nom_utilisateur nom_utilisateur VARCHAR(255) DEFAULT NULL');
    }
}
