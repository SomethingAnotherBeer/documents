<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230113191644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tokens (id INT AUTO_INCREMENT NOT NULL, user_rel_id INT NOT NULL, token_key VARCHAR(255) NOT NULL, token_untill INT NOT NULL, UNIQUE INDEX UNIQ_AA5A118E2B58BAF0 (user_rel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tokens ADD CONSTRAINT FK_AA5A118E2B58BAF0 FOREIGN KEY (user_rel_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tokens DROP FOREIGN KEY FK_AA5A118E2B58BAF0');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('ALTER TABLE `users` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
