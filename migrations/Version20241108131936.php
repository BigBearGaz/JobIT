<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108131936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_entreprise (category_id INT NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_2B07AA1E12469DE2 (category_id), INDEX IDX_2B07AA1EA4AEAFEA (entreprise_id), PRIMARY KEY(category_id, entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE distanciel (id INT AUTO_INCREMENT NOT NULL, distanciel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, presentation LONGTEXT NOT NULL, lieu VARCHAR(255) NOT NULL, site_web VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, type_contrat_id INT NOT NULL, category_id INT NOT NULL, auteur_id INT NOT NULL, temps_id INT DEFAULT NULL, distanciel_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_publication DATE NOT NULL, date_modification DATE NOT NULL, lieu VARCHAR(255) NOT NULL, duree VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) NOT NULL, competences VARCHAR(255) DEFAULT NULL, salaire VARCHAR(255) NOT NULL, INDEX IDX_AF86866F520D03A (type_contrat_id), INDEX IDX_AF86866F12469DE2 (category_id), INDEX IDX_AF86866F60BB6FE6 (auteur_id), INDEX IDX_AF86866F3984CC5A (temps_id), INDEX IDX_AF86866F4E5932E3 (distanciel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temps (id INT AUTO_INCREMENT NOT NULL, temps VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_contrat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, statut_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, adresse VARCHAR(255) NOT NULL, tel VARCHAR(20) NOT NULL, photo VARCHAR(255) NOT NULL, entreprise VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D649F6203804 (statut_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_offre (user_id INT NOT NULL, offre_id INT NOT NULL, INDEX IDX_4D447D37A76ED395 (user_id), INDEX IDX_4D447D374CC8505A (offre_id), PRIMARY KEY(user_id, offre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_entreprise ADD CONSTRAINT FK_2B07AA1E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_entreprise ADD CONSTRAINT FK_2B07AA1EA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F520D03A FOREIGN KEY (type_contrat_id) REFERENCES type_contrat (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F3984CC5A FOREIGN KEY (temps_id) REFERENCES temps (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F4E5932E3 FOREIGN KEY (distanciel_id) REFERENCES distanciel (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE user_offre ADD CONSTRAINT FK_4D447D37A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_offre ADD CONSTRAINT FK_4D447D374CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_entreprise DROP FOREIGN KEY FK_2B07AA1E12469DE2');
        $this->addSql('ALTER TABLE category_entreprise DROP FOREIGN KEY FK_2B07AA1EA4AEAFEA');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F520D03A');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F12469DE2');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F60BB6FE6');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F3984CC5A');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F4E5932E3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6203804');
        $this->addSql('ALTER TABLE user_offre DROP FOREIGN KEY FK_4D447D37A76ED395');
        $this->addSql('ALTER TABLE user_offre DROP FOREIGN KEY FK_4D447D374CC8505A');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_entreprise');
        $this->addSql('DROP TABLE distanciel');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE temps');
        $this->addSql('DROP TABLE type_contrat');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_offre');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
