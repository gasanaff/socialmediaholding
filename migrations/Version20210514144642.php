<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512144642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_like ADD by_track_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playlist_like ADD CONSTRAINT FK_C7A77D1FF785DCA FOREIGN KEY (by_track_id) REFERENCES track (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C7A77D1FF785DCA ON playlist_like (by_track_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE playlist_like DROP CONSTRAINT FK_C7A77D1FF785DCA');
        $this->addSql('DROP INDEX IDX_C7A77D1FF785DCA');
        $this->addSql('ALTER TABLE playlist_like DROP by_track_id');
    }
}
