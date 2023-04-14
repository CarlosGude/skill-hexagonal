<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414184129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Article (id INT UNSIGNED AUTO_INCREMENT NOT NULL, author_id INT UNSIGNED NOT NULL, title VARCHAR(150) NOT NULL, body LONGTEXT NOT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CD8737FAF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Author (id INT UNSIGNED AUTO_INCREMENT NOT NULL, emil LONGTEXT NOT NULL, name LONGTEXT NOT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Article ADD CONSTRAINT FK_CD8737FAF675F31B FOREIGN KEY (author_id) REFERENCES Author (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Article DROP FOREIGN KEY FK_CD8737FAF675F31B');
        $this->addSql('DROP TABLE Article');
        $this->addSql('DROP TABLE Author');
    }
}
