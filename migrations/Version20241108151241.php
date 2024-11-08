<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108151241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, student_id_id INT NOT NULL, offer_id_id INT DEFAULT NULL, date DATETIME NOT NULL, cover_letter LONGTEXT NOT NULL, INDEX IDX_A45BDDC1F773E7CA (student_id_id), INDEX IDX_A45BDDC1FC69E3BE (offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_64C19C1FC69E3BE (offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, name VARCHAR(255) NOT NULL, sector VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, type VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_D8698A769D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id_id INT NOT NULL, recipient_id_id INT DEFAULT NULL, application_id_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_B6BD307F6061F7CF (sender_id_id), INDEX IDX_B6BD307F2B6945EC (recipient_id_id), INDEX IDX_B6BD307F9CD0792D (application_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_BF5476CA9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, salary DOUBLE PRECISION DEFAULT NULL, INDEX IDX_29D6873E979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill_offer (skill_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_CFE14025585C142 (skill_id), INDEX IDX_CFE140253C674EE (offer_id), PRIMARY KEY(skill_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill_student (skill_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_ADD6311A5585C142 (skill_id), INDEX IDX_ADD6311ACB944F1A (student_id), PRIMARY KEY(skill_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, education VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', user_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1F773E7CA FOREIGN KEY (student_id_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FBF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A769D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6061F7CF FOREIGN KEY (sender_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2B6945EC FOREIGN KEY (recipient_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9CD0792D FOREIGN KEY (application_id_id) REFERENCES application (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE skill_offer ADD CONSTRAINT FK_CFE14025585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_offer ADD CONSTRAINT FK_CFE140253C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_student ADD CONSTRAINT FK_ADD6311A5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_student ADD CONSTRAINT FK_ADD6311ACB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1F773E7CA');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1FC69E3BE');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1FC69E3BE');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FBF396750');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A769D86650F');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F6061F7CF');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2B6945EC');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9CD0792D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA9D86650F');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E979B1AD6');
        $this->addSql('ALTER TABLE skill_offer DROP FOREIGN KEY FK_CFE14025585C142');
        $this->addSql('ALTER TABLE skill_offer DROP FOREIGN KEY FK_CFE140253C674EE');
        $this->addSql('ALTER TABLE skill_student DROP FOREIGN KEY FK_ADD6311A5585C142');
        $this->addSql('ALTER TABLE skill_student DROP FOREIGN KEY FK_ADD6311ACB944F1A');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF396750');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE skill_offer');
        $this->addSql('DROP TABLE skill_student');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
