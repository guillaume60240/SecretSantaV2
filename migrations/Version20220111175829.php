<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111175829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE santa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE santa_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE santa (id INT NOT NULL, user_relation_id INT NOT NULL, santa_list_relation_id INT NOT NULL, give_gift_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C865F6729B4D58CE ON santa (user_relation_id)');
        $this->addSql('CREATE INDEX IDX_C865F672F480C394 ON santa (santa_list_relation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C865F67267E6BEF6 ON santa (give_gift_id)');
        $this->addSql('CREATE TABLE santa_list (id INT NOT NULL, user_relation_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, event_date DATE NOT NULL, generated BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_74CD265A9B4D58CE ON santa_list (user_relation_id)');
        $this->addSql('ALTER TABLE santa ADD CONSTRAINT FK_C865F6729B4D58CE FOREIGN KEY (user_relation_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE santa ADD CONSTRAINT FK_C865F672F480C394 FOREIGN KEY (santa_list_relation_id) REFERENCES santa_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE santa ADD CONSTRAINT FK_C865F67267E6BEF6 FOREIGN KEY (give_gift_id) REFERENCES santa (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE santa_list ADD CONSTRAINT FK_74CD265A9B4D58CE FOREIGN KEY (user_relation_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER last_name SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE santa DROP CONSTRAINT FK_C865F67267E6BEF6');
        $this->addSql('ALTER TABLE santa DROP CONSTRAINT FK_C865F672F480C394');
        $this->addSql('DROP SEQUENCE santa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE santa_list_id_seq CASCADE');
        $this->addSql('DROP TABLE santa');
        $this->addSql('DROP TABLE santa_list');
        $this->addSql('ALTER TABLE "user" ALTER last_name DROP NOT NULL');
    }
}
