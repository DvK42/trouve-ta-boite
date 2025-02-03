<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203230728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE description description LONGTEXT DEFAULT NULL, CHANGE location location VARCHAR(255) NOT NULL, CHANGE employee_count employee_count INT NOT NULL, CHANGE year_founded year_founded INT NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE postal_code postal_code VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE location location VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(20) DEFAULT NULL, CHANGE employee_count employee_count INT DEFAULT NULL, CHANGE year_founded year_founded INT DEFAULT NULL');
    }
}
