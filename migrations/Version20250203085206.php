<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203085206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acceptations_cgv (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, cgv_id INT NOT NULL, date_acceptation DATETIME NOT NULL, INDEX IDX_4699D63982EA2E54 (commande_id), INDEX IDX_4699D639C3E49468 (cgv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acceptations_politique_confidentialite (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, politique_confidentialite_id INT NOT NULL, date_acceptation DATETIME NOT NULL, INDEX IDX_A7A7F2A282EA2E54 (commande_id), INDEX IDX_A7A7F2A21C21A50E (politique_confidentialite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cgv (id INT AUTO_INCREMENT NOT NULL, version INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cgvsections (id INT AUTO_INCREMENT NOT NULL, version_id INT NOT NULL, section VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_update DATETIME DEFAULT NULL, INDEX IDX_5C8778724BBC2705 (version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, nom_client VARCHAR(255) DEFAULT NULL, prenom_client VARCHAR(255) DEFAULT NULL, mail_client VARCHAR(255) DEFAULT NULL, telephone_client VARCHAR(255) DEFAULT NULL, date_recuperation DATETIME DEFAULT NULL, montant_total NUMERIC(10, 2) DEFAULT NULL, statut INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes_produits (commande_id INT NOT NULL, produit_id INT NOT NULL, montant NUMERIC(10, 2) DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_D58023F082EA2E54 (commande_id), INDEX IDX_D58023F0F347EFB (produit_id), PRIMARY KEY(commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE politique_confidentialite (id INT AUTO_INCREMENT NOT NULL, version INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE politique_confidentialite_sections (id INT AUTO_INCREMENT NOT NULL, version_id INT DEFAULT NULL, section VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_update DATETIME DEFAULT NULL, INDEX IDX_C0540DED4BBC2705 (version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prix NUMERIC(10, 2) DEFAULT NULL, unite_vente VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_BE2DDF8C9F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promos (id INT AUTO_INCREMENT NOT NULL, id_produit_id INT DEFAULT NULL, qte INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, montant NUMERIC(10, 2) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, INDEX IDX_31D1F705AABEFE2C (id_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, id_role_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, nom_utilisateur VARCHAR(255) DEFAULT NULL, mdp VARCHAR(255) DEFAULT NULL, INDEX IDX_497B315E89E8BDC (id_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acceptations_cgv ADD CONSTRAINT FK_4699D63982EA2E54 FOREIGN KEY (commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE acceptations_cgv ADD CONSTRAINT FK_4699D639C3E49468 FOREIGN KEY (cgv_id) REFERENCES cgv (id)');
        $this->addSql('ALTER TABLE acceptations_politique_confidentialite ADD CONSTRAINT FK_A7A7F2A282EA2E54 FOREIGN KEY (commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE acceptations_politique_confidentialite ADD CONSTRAINT FK_A7A7F2A21C21A50E FOREIGN KEY (politique_confidentialite_id) REFERENCES politique_confidentialite (id)');
        $this->addSql('ALTER TABLE cgvsections ADD CONSTRAINT FK_5C8778724BBC2705 FOREIGN KEY (version_id) REFERENCES cgv (id)');
        $this->addSql('ALTER TABLE commandes_produits ADD CONSTRAINT FK_D58023F082EA2E54 FOREIGN KEY (commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE commandes_produits ADD CONSTRAINT FK_D58023F0F347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE politique_confidentialite_sections ADD CONSTRAINT FK_C0540DED4BBC2705 FOREIGN KEY (version_id) REFERENCES politique_confidentialite (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C9F34925F FOREIGN KEY (id_categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E89E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acceptations_cgv DROP FOREIGN KEY FK_4699D63982EA2E54');
        $this->addSql('ALTER TABLE acceptations_cgv DROP FOREIGN KEY FK_4699D639C3E49468');
        $this->addSql('ALTER TABLE acceptations_politique_confidentialite DROP FOREIGN KEY FK_A7A7F2A282EA2E54');
        $this->addSql('ALTER TABLE acceptations_politique_confidentialite DROP FOREIGN KEY FK_A7A7F2A21C21A50E');
        $this->addSql('ALTER TABLE cgvsections DROP FOREIGN KEY FK_5C8778724BBC2705');
        $this->addSql('ALTER TABLE commandes_produits DROP FOREIGN KEY FK_D58023F082EA2E54');
        $this->addSql('ALTER TABLE commandes_produits DROP FOREIGN KEY FK_D58023F0F347EFB');
        $this->addSql('ALTER TABLE politique_confidentialite_sections DROP FOREIGN KEY FK_C0540DED4BBC2705');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C9F34925F');
        $this->addSql('ALTER TABLE promos DROP FOREIGN KEY FK_31D1F705AABEFE2C');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315E89E8BDC');
        $this->addSql('DROP TABLE acceptations_cgv');
        $this->addSql('DROP TABLE acceptations_politique_confidentialite');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE cgv');
        $this->addSql('DROP TABLE cgvsections');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commandes_produits');
        $this->addSql('DROP TABLE politique_confidentialite');
        $this->addSql('DROP TABLE politique_confidentialite_sections');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE promos');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
