<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617093754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, INDEX IDX_5A8A6C8D5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE post_value (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, post_id INT DEFAULT NULL, template_field_id INT DEFAULT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_8A6CD4DB5DA0FB8 (template_id), INDEX IDX_8A6CD4DB4B89032C (post_id), INDEX IDX_8A6CD4DB1B6137C3 (template_field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, system_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, active SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE template_field (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, system_name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, required SMALLINT NOT NULL, options JSON DEFAULT NULL, `order` INT DEFAULT NULL, type TINYTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_682396975DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_value ADD CONSTRAINT FK_8A6CD4DB5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_value ADD CONSTRAINT FK_8A6CD4DB4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_value ADD CONSTRAINT FK_8A6CD4DB1B6137C3 FOREIGN KEY (template_field_id) REFERENCES template_field (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template_field ADD CONSTRAINT FK_682396975DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_value DROP FOREIGN KEY FK_8A6CD4DB5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_value DROP FOREIGN KEY FK_8A6CD4DB4B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_value DROP FOREIGN KEY FK_8A6CD4DB1B6137C3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template_field DROP FOREIGN KEY FK_682396975DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post_value
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE template
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE template_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
