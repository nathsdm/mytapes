<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115083344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gallery (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, member_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, published BOOLEAN NOT NULL, name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_472B783A7597D3FE FOREIGN KEY (member_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_472B783A7597D3FE ON gallery (member_id)');
        $this->addSql('CREATE TABLE gallery_tape (gallery_id INTEGER NOT NULL, tape_id INTEGER NOT NULL, PRIMARY KEY(gallery_id, tape_id), CONSTRAINT FK_DF48D6D4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DF48D6D2AC90C65 FOREIGN KEY (tape_id) REFERENCES tape (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DF48D6D4E7AF8F ON gallery_tape (gallery_id)');
        $this->addSql('CREATE INDEX IDX_DF48D6D2AC90C65 ON gallery_tape (tape_id)');
        $this->addSql('CREATE TABLE inventory (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, member_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_B12D4A367597D3FE FOREIGN KEY (member_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B12D4A367597D3FE ON inventory (member_id)');
        $this->addSql('CREATE TABLE member (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, bio CLOB DEFAULT NULL, birth DATE DEFAULT NULL, creation DATE DEFAULT NULL, CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70E4FA78A76ED395 ON member (user_id)');
        $this->addSql('CREATE TABLE tape (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, inventory_id INTEGER NOT NULL, year INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, image_size INTEGER DEFAULT NULL, is_public BOOLEAN DEFAULT 0 NOT NULL, likes INTEGER NOT NULL, member_likes CLOB DEFAULT NULL --(DC2Type:array)
        , CONSTRAINT FK_9EEBA5E19EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9EEBA5E19EEA759 ON tape (inventory_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_tape');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE tape');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
