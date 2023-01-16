<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116114541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, user_rel_id INT NOT NULL, document_key VARCHAR(255) NOT NULL, document_status VARCHAR(25) NOT NULL, document_payload LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', create_at INT NOT NULL, modify_at INT NOT NULL, INDEX IDX_A2B072882B58BAF0 (user_rel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072882B58BAF0 FOREIGN KEY (user_rel_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A762B58BAF0');
        $this->addSql('DROP TABLE document');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, user_rel_id INT NOT NULL, document_key VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, document_payload LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', create_at INT NOT NULL, modify_at INT NOT NULL, document_status VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D8698A762B58BAF0 (user_rel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A762B58BAF0 FOREIGN KEY (user_rel_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072882B58BAF0');
        $this->addSql('DROP TABLE documents');
    }
}
