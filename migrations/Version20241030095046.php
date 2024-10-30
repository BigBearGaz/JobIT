<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030095046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_offre DROP FOREIGN KEY FK_4D447D374CC8505A');
        $this->addSql('ALTER TABLE user_offre DROP FOREIGN KEY FK_4D447D37A76ED395');
        $this->addSql('DROP TABLE user_offre');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_offre (user_id INT NOT NULL, offre_id INT NOT NULL, INDEX IDX_4D447D374CC8505A (offre_id), INDEX IDX_4D447D37A76ED395 (user_id), PRIMARY KEY(user_id, offre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_offre ADD CONSTRAINT FK_4D447D374CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_offre ADD CONSTRAINT FK_4D447D37A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
