<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228190044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pill DROP FOREIGN KEY FK_803186F79D86650F');
        $this->addSql('DROP INDEX IDX_803186F79D86650F ON pill');
        $this->addSql('ALTER TABLE pill CHANGE user_id_id user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE pill ADD CONSTRAINT FK_803186F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_803186F7A76ED395 ON pill (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pill DROP FOREIGN KEY FK_803186F7A76ED395');
        $this->addSql('DROP INDEX IDX_803186F7A76ED395 ON pill');
        $this->addSql('ALTER TABLE pill CHANGE user_id user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE pill ADD CONSTRAINT FK_803186F79D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_803186F79D86650F ON pill (user_id_id)');
    }
}
