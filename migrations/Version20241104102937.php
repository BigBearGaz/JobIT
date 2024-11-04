<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104102937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE distanciel (id INT AUTO_INCREMENT NOT NULL, distanciel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temps (id INT AUTO_INCREMENT NOT NULL, temps VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offre ADD temps_id INT DEFAULT NULL, ADD distanciel_id INT NOT NULL, CHANGE temps salaire VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F3984CC5A FOREIGN KEY (temps_id) REFERENCES temps (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F4E5932E3 FOREIGN KEY (distanciel_id) REFERENCES distanciel (id)');
        $this->addSql('CREATE INDEX IDX_AF86866F3984CC5A ON offre (temps_id)');
        $this->addSql('CREATE INDEX IDX_AF86866F4E5932E3 ON offre (distanciel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F4E5932E3');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F3984CC5A');
        $this->addSql('DROP TABLE distanciel');
        $this->addSql('DROP TABLE temps');
        $this->addSql('DROP INDEX IDX_AF86866F3984CC5A ON offre');
        $this->addSql('DROP INDEX IDX_AF86866F4E5932E3 ON offre');
        $this->addSql('ALTER TABLE offre DROP temps_id, DROP distanciel_id, CHANGE salaire temps VARCHAR(255) NOT NULL');
    }
}
