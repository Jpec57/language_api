<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210714192058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE srscard_tag (srscard_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_18EC380D5DE6E93 (srscard_id), INDEX IDX_18EC380BAD26311 (tag_id), PRIMARY KEY(srscard_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE srscard_tag ADD CONSTRAINT FK_18EC380D5DE6E93 FOREIGN KEY (srscard_id) REFERENCES srscard (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE srscard_tag ADD CONSTRAINT FK_18EC380BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE srscard_tag DROP FOREIGN KEY FK_18EC380BAD26311');
        $this->addSql('DROP TABLE srscard_tag');
        $this->addSql('DROP TABLE tag');
    }
}
