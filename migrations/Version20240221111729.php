<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221111729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (
            id VARCHAR(255),
            firstname VARCHAR(255) ,
            lastname VARCHAR(255) ,
            ssn VARCHAR(255),
            phone VARCHAR(255),
            email VARCHAR(255),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loan (
            id VARCHAR(255) NOT NULL,
            customer_id TEXT,
            reference TEXT,
            status VARCHAR(255),
            amount_issued DECIMAL(10, 2),
            amount_to_pay DECIMAL(10, 2), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (
            id INTEGER AUTO_INCREMENT NOT NULL, 
            payment_date DATE, 
            payer_name TEXT, 
            payer_surname TEXT,
            amount DECIMAL(10, 2),
            national_security_number VARCHAR(255),
            description TEXT,
            payment_reference TEXT UNIQUE, 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_order (
            id INTEGER AUTO_INCREMENT NOT NULL, 
            amount DECIMAL(10, 2),
            date DATE,
            reference TEXT UNIQUE, 
            payer_name TEXT, 
            payer_surname TEXT,
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE payment');
    }
}
