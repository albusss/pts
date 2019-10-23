<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023092750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE post_likes (post_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(post_id, user_id))');
        $this->addSql('CREATE INDEX IDX_DED1C2924B89032C ON post_likes (post_id)');
        $this->addSql('CREATE INDEX IDX_DED1C292A76ED395 ON post_likes (user_id)');
        $this->addSql('ALTER TABLE post_likes ADD CONSTRAINT FK_DED1C2924B89032C FOREIGN KEY (post_id) REFERENCES micro_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_likes ADD CONSTRAINT FK_DED1C292A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE post_likes');
    }
}
