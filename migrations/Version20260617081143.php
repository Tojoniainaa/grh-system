<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260617081143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agents (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(50) NOT NULL, sexe VARCHAR(1) NOT NULL, date_naissance DATE DEFAULT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, pere VARCHAR(50) DEFAULT NULL, pere_decede VARCHAR(3) DEFAULT NULL, mere VARCHAR(50) DEFAULT NULL, mere_decede VARCHAR(3) DEFAULT NULL, cin VARCHAR(20) DEFAULT NULL, date_cin DATE DEFAULT NULL, lieu_delivrance_cin VARCHAR(255) DEFAULT NULL, contact VARCHAR(10) DEFAULT NULL, nom_photos VARCHAR(255) DEFAULT NULL, nom_pdf VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agents');
    }
}
