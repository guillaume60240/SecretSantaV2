<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201134601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE santa_list ADD send_mail_to_santas BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE santa_list ADD send_notification_for_generated_list BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE santa_list ADD send_notification_for_santa_visit BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE santa_list DROP send_mail_to_santas');
        $this->addSql('ALTER TABLE santa_list DROP send_notification_for_generated_list');
        $this->addSql('ALTER TABLE santa_list DROP send_notification_for_santa_visit');
    }
}
