<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512085403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE playlist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE playlist_like_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE track_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE track_like_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE playlist (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE playlist_like (id INT NOT NULL, _user_id INT NOT NULL, playlist_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7A77D1D32632E8 ON playlist_like (_user_id)');
        $this->addSql('CREATE INDEX IDX_C7A77D16BBD148 ON playlist_like (playlist_id)');
        $this->addSql('CREATE TABLE track (id INT NOT NULL, playlist_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D6E3F8A66BBD148 ON track (playlist_id)');
        $this->addSql('CREATE TABLE track_like (id INT NOT NULL, _user_id INT NOT NULL, track_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_313568B6D32632E8 ON track_like (_user_id)');
        $this->addSql('CREATE INDEX IDX_313568B65ED23C43 ON track_like (track_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE playlist_like ADD CONSTRAINT FK_C7A77D1D32632E8 FOREIGN KEY (_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_like ADD CONSTRAINT FK_C7A77D16BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A66BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE track_like ADD CONSTRAINT FK_313568B6D32632E8 FOREIGN KEY (_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE track_like ADD CONSTRAINT FK_313568B65ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE playlist_like DROP CONSTRAINT FK_C7A77D16BBD148');
        $this->addSql('ALTER TABLE track DROP CONSTRAINT FK_D6E3F8A66BBD148');
        $this->addSql('ALTER TABLE track_like DROP CONSTRAINT FK_313568B65ED23C43');
        $this->addSql('ALTER TABLE playlist_like DROP CONSTRAINT FK_C7A77D1D32632E8');
        $this->addSql('ALTER TABLE track_like DROP CONSTRAINT FK_313568B6D32632E8');
        $this->addSql('DROP SEQUENCE playlist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE playlist_like_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE track_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE track_like_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_like');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE track_like');
        $this->addSql('DROP TABLE "user"');
    }
}
