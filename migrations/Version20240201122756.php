<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201122756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_it (id INT AUTO_INCREMENT NOT NULL, wall_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', color VARCHAR(255) NOT NULL, font_size_pixels INT NOT NULL, content LONGTEXT DEFAULT NULL, position_x INT NOT NULL, position_y INT NOT NULL, size VARCHAR(255) NOT NULL, deadline DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_563E1348C33923F1 (wall_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_it ADD CONSTRAINT FK_563E1348C33923F1 FOREIGN KEY (wall_id) REFERENCES wall (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_it DROP FOREIGN KEY FK_563E1348C33923F1');
        $this->addSql('DROP TABLE post_it');
    }
}
