<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251026142511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_game (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, user_id INT DEFAULT NULL, games_played INT NOT NULL, INDEX IDX_CD2D28B3E48FD905 (game_id), INDEX IDX_CD2D28B3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_place (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, place_id INT DEFAULT NULL, INDEX IDX_C29578E9A76ED395 (user_id), INDEX IDX_C29578E9DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, min_players INT NOT NULL, max_players INT NOT NULL, description LONGTEXT DEFAULT NULL, img_url VARCHAR(255) DEFAULT NULL, INDEX IDX_232B318C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meetup (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, place_id INT DEFAULT NULL, owner_id INT NOT NULL, time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', capacity INT NOT NULL, INDEX IDX_9377E28E48FD905 (game_id), INDEX IDX_9377E28DA6A219 (place_id), INDEX IDX_9377E287E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, meetup_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, sender_id INT NOT NULL, content LONGTEXT NOT NULL, time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) NOT NULL, INDEX IDX_B6BD307F591E2316 (meetup_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personality_trait (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, adress_street VARCHAR(255) NOT NULL, adress_number INT NOT NULL, capacity INT DEFAULT NULL, name VARCHAR(255) NOT NULL, img_url VARCHAR(255) NOT NULL, INDEX IDX_741D53CD8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, bio LONGTEXT DEFAULT NULL, img_url VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, INDEX IDX_8D93D6498BAC62AF (city_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_personality_trait (user_id INT NOT NULL, personality_trait_id INT NOT NULL, INDEX IDX_A73D5927A76ED395 (user_id), INDEX IDX_A73D5927D673EC81 (personality_trait_id), PRIMARY KEY(user_id, personality_trait_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_meetup (user_id INT NOT NULL, meetup_id INT NOT NULL, INDEX IDX_6C656515A76ED395 (user_id), INDEX IDX_6C656515591E2316 (meetup_id), PRIMARY KEY(user_id, meetup_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_game ADD CONSTRAINT FK_CD2D28B3E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE favorite_game ADD CONSTRAINT FK_CD2D28B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite_place ADD CONSTRAINT FK_C29578E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite_place ADD CONSTRAINT FK_C29578E9DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE meetup ADD CONSTRAINT FK_9377E28E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE meetup ADD CONSTRAINT FK_9377E28DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE meetup ADD CONSTRAINT FK_9377E287E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F591E2316 FOREIGN KEY (meetup_id) REFERENCES meetup (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE user_personality_trait ADD CONSTRAINT FK_A73D5927A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_personality_trait ADD CONSTRAINT FK_A73D5927D673EC81 FOREIGN KEY (personality_trait_id) REFERENCES personality_trait (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_meetup ADD CONSTRAINT FK_6C656515A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_meetup ADD CONSTRAINT FK_6C656515591E2316 FOREIGN KEY (meetup_id) REFERENCES meetup (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_game DROP FOREIGN KEY FK_CD2D28B3E48FD905');
        $this->addSql('ALTER TABLE favorite_game DROP FOREIGN KEY FK_CD2D28B3A76ED395');
        $this->addSql('ALTER TABLE favorite_place DROP FOREIGN KEY FK_C29578E9A76ED395');
        $this->addSql('ALTER TABLE favorite_place DROP FOREIGN KEY FK_C29578E9DA6A219');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C12469DE2');
        $this->addSql('ALTER TABLE meetup DROP FOREIGN KEY FK_9377E28E48FD905');
        $this->addSql('ALTER TABLE meetup DROP FOREIGN KEY FK_9377E28DA6A219');
        $this->addSql('ALTER TABLE meetup DROP FOREIGN KEY FK_9377E287E3C61F9');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F591E2316');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD8BAC62AF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE user_personality_trait DROP FOREIGN KEY FK_A73D5927A76ED395');
        $this->addSql('ALTER TABLE user_personality_trait DROP FOREIGN KEY FK_A73D5927D673EC81');
        $this->addSql('ALTER TABLE user_meetup DROP FOREIGN KEY FK_6C656515A76ED395');
        $this->addSql('ALTER TABLE user_meetup DROP FOREIGN KEY FK_6C656515591E2316');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE favorite_game');
        $this->addSql('DROP TABLE favorite_place');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE meetup');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE personality_trait');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_personality_trait');
        $this->addSql('DROP TABLE user_meetup');
    }
}
