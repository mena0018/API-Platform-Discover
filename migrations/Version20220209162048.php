<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209162048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Rating (id INT AUTO_INCREMENT NOT NULL, bookmark_id INT NOT NULL, user_id INT NOT NULL, value SMALLINT NOT NULL, INDEX IDX_DF25231492741D25 (bookmark_id), INDEX IDX_DF252314A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Rating ADD CONSTRAINT FK_DF25231492741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id)');
        $this->addSql('ALTER TABLE Rating ADD CONSTRAINT FK_DF252314A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Rating');
    }
}
