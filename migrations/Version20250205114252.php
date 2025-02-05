<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205114252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offer_skill (offer_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_DD10999E53C674EE (offer_id), INDEX IDX_DD10999E5585C142 (skill_id), PRIMARY KEY(offer_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_skill (student_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_D60A9DEACB944F1A (student_id), INDEX IDX_D60A9DEA5585C142 (skill_id), PRIMARY KEY(student_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_skill ADD CONSTRAINT FK_DD10999E53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_skill ADD CONSTRAINT FK_DD10999E5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_skill ADD CONSTRAINT FK_D60A9DEACB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_skill ADD CONSTRAINT FK_D60A9DEA5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_offer DROP FOREIGN KEY FK_CFE14025585C142');
        $this->addSql('ALTER TABLE skill_offer DROP FOREIGN KEY FK_CFE140253C674EE');
        $this->addSql('ALTER TABLE skill_student DROP FOREIGN KEY FK_ADD6311ACB944F1A');
        $this->addSql('ALTER TABLE skill_student DROP FOREIGN KEY FK_ADD6311A5585C142');
        $this->addSql('DROP TABLE skill_offer');
        $this->addSql('DROP TABLE skill_student');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE skill_offer (skill_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_CFE14025585C142 (skill_id), INDEX IDX_CFE140253C674EE (offer_id), PRIMARY KEY(skill_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE skill_student (skill_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_ADD6311A5585C142 (skill_id), INDEX IDX_ADD6311ACB944F1A (student_id), PRIMARY KEY(skill_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE skill_offer ADD CONSTRAINT FK_CFE14025585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_offer ADD CONSTRAINT FK_CFE140253C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_student ADD CONSTRAINT FK_ADD6311ACB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_student ADD CONSTRAINT FK_ADD6311A5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_skill DROP FOREIGN KEY FK_DD10999E53C674EE');
        $this->addSql('ALTER TABLE offer_skill DROP FOREIGN KEY FK_DD10999E5585C142');
        $this->addSql('ALTER TABLE student_skill DROP FOREIGN KEY FK_D60A9DEACB944F1A');
        $this->addSql('ALTER TABLE student_skill DROP FOREIGN KEY FK_D60A9DEA5585C142');
        $this->addSql('DROP TABLE offer_skill');
        $this->addSql('DROP TABLE student_skill');
    }
}
