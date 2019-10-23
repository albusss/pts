<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023103546 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP TABLE notification');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, user_id INT DEFAULT NULL, liked_by_id INT DEFAULT NULL, micro_post_id INT DEFAULT NULL, seen BOOLEAN NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAB4622EC2 ON notification (liked_by_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA11E37CEA ON notification (micro_post_id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAB4622EC2 FOREIGN KEY (liked_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA11E37CEA FOREIGN KEY (micro_post_id) REFERENCES micro_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
