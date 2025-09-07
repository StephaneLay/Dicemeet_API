<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250907151052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F441B8B65');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F56AE248B');
        $this->addSql('DROP INDEX IDX_B6BD307F441B8B65 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F56AE248B ON message');
        $this->addSql('ALTER TABLE message DROP user1_id, DROP user2_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD user1_id INT DEFAULT NULL, ADD user2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F56AE248B FOREIGN KEY (user1_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6BD307F441B8B65 ON message (user2_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F56AE248B ON message (user1_id)');
    }
}
