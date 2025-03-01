<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301093007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pill_intake_log (id INT AUTO_INCREMENT NOT NULL, pill_id INT NOT NULL, status VARCHAR(255) NOT NULL, scheduled_time DATETIME NOT NULL, actual_time DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CD2AC6DCEACD9F12 (pill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pill_intake_log ADD CONSTRAINT FK_CD2AC6DCEACD9F12 FOREIGN KEY (pill_id) REFERENCES pill (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pill_intake_log DROP FOREIGN KEY FK_CD2AC6DCEACD9F12');
        $this->addSql('DROP TABLE pill_intake_log');
    }
}
