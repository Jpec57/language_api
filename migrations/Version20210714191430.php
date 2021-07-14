<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210714191430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE context_sentence (id INT AUTO_INCREMENT NOT NULL, sentence VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_level (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE srscard (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, level INT NOT NULL, next_availability_date DATETIME DEFAULT NULL, correct_count INT NOT NULL, error_count INT NOT NULL, INDEX IDX_8F2464D2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vocab_card (id INT AUTO_INCREMENT NOT NULL, word_to_translate VARCHAR(255) NOT NULL, english_word VARCHAR(255) DEFAULT NULL, alternative_writings LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', synonyms LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', translations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', user_notes LONGTEXT DEFAULT NULL, translation_locale VARCHAR(5) NOT NULL, card_locale VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vocab_card_language_level (vocab_card_id INT NOT NULL, language_level_id INT NOT NULL, INDEX IDX_93C31FC9918384C3 (vocab_card_id), INDEX IDX_93C31FC93313139D (language_level_id), PRIMARY KEY(vocab_card_id, language_level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vocab_card_context_sentence (vocab_card_id INT NOT NULL, context_sentence_id INT NOT NULL, INDEX IDX_4952DA34918384C3 (vocab_card_id), INDEX IDX_4952DA34EA7249CC (context_sentence_id), PRIMARY KEY(vocab_card_id, context_sentence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE srscard ADD CONSTRAINT FK_8F2464D2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE vocab_card_language_level ADD CONSTRAINT FK_93C31FC9918384C3 FOREIGN KEY (vocab_card_id) REFERENCES vocab_card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vocab_card_language_level ADD CONSTRAINT FK_93C31FC93313139D FOREIGN KEY (language_level_id) REFERENCES language_level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vocab_card_context_sentence ADD CONSTRAINT FK_4952DA34918384C3 FOREIGN KEY (vocab_card_id) REFERENCES vocab_card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vocab_card_context_sentence ADD CONSTRAINT FK_4952DA34EA7249CC FOREIGN KEY (context_sentence_id) REFERENCES context_sentence (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vocab_card_context_sentence DROP FOREIGN KEY FK_4952DA34EA7249CC');
        $this->addSql('ALTER TABLE vocab_card_language_level DROP FOREIGN KEY FK_93C31FC93313139D');
        $this->addSql('ALTER TABLE vocab_card_language_level DROP FOREIGN KEY FK_93C31FC9918384C3');
        $this->addSql('ALTER TABLE vocab_card_context_sentence DROP FOREIGN KEY FK_4952DA34918384C3');
        $this->addSql('DROP TABLE context_sentence');
        $this->addSql('DROP TABLE language_level');
        $this->addSql('DROP TABLE srscard');
        $this->addSql('DROP TABLE vocab_card');
        $this->addSql('DROP TABLE vocab_card_language_level');
        $this->addSql('DROP TABLE vocab_card_context_sentence');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON `user`');
    }
}
