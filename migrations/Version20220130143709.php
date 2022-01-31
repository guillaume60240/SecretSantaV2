<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220130143709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE santa_santa (santa_source INT NOT NULL, santa_target INT NOT NULL, PRIMARY KEY(santa_source, santa_target))');
        $this->addSql('CREATE INDEX IDX_5087D310FCAE9D22 ON santa_santa (santa_source)');
        $this->addSql('CREATE INDEX IDX_5087D310E54BCDAD ON santa_santa (santa_target)');
        $this->addSql('ALTER TABLE santa_santa ADD CONSTRAINT FK_5087D310FCAE9D22 FOREIGN KEY (santa_source) REFERENCES santa (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE santa_santa ADD CONSTRAINT FK_5087D310E54BCDAD FOREIGN KEY (santa_target) REFERENCES santa (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE santa_santa');
    }
}
