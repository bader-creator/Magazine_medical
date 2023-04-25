<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425134000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, quantity VARCHAR(255) NOT NULL, date_commande DATE NOT NULL, prix VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_user (commande_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E6FFD7AA82EA2E54 (commande_id), INDEX IDX_E6FFD7AAA76ED395 (user_id), PRIMARY KEY(commande_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, createur_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, prix VARCHAR(255) NOT NULL, quantity VARCHAR(255) NOT NULL, is_desponible TINYINT(1) NOT NULL, file VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04AD73A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, sujet VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, docteur_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, local VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, INDEX IDX_65E8AA0ACF22540A (docteur_id), INDEX IDX_65E8AA0A6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, date_naissane DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_user ADD CONSTRAINT FK_E6FFD7AA82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_user ADD CONSTRAINT FK_E6FFD7AAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD73A201E5 FOREIGN KEY (createur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ACF22540A FOREIGN KEY (docteur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_user DROP FOREIGN KEY FK_E6FFD7AA82EA2E54');
        $this->addSql('ALTER TABLE commande_user DROP FOREIGN KEY FK_E6FFD7AAA76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD73A201E5');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ACF22540A');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_user');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE `user`');
    }
}
