<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210603054642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employees (id INT AUTO_INCREMENT NOT NULL, designation_id INT NOT NULL, boss_id INT DEFAULT NULL, emp_name VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, salary INT NOT NULL, profile_pic VARCHAR(255) NOT NULL, INDEX IDX_BA82C300FAC7D83F (designation_id), INDEX IDX_BA82C300261FB672 (boss_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300FAC7D83F FOREIGN KEY (designation_id) REFERENCES designations (id)');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300261FB672 FOREIGN KEY (boss_id) REFERENCES employees (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C300261FB672');
        $this->addSql('DROP TABLE employees');
    }
}
