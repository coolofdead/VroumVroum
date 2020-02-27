<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200214094549 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price_ht DOUBLE PRECISION NOT NULL, promo TINYINT(1) NOT NULL, stock INT NOT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advert CHANGE description description LONGTEXT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE car_year car_year INT DEFAULT NULL, CHANGE nb_km nb_km INT DEFAULT NULL, CHANGE nb_days nb_days INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP login, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE advert CHANGE description description TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, CHANGE city city VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, CHANGE car_year car_year INT NOT NULL, CHANGE nb_km nb_km INT NOT NULL, CHANGE nb_days nb_days INT NOT NULL, CHANGE price price INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD login VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, CHANGE email email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`');
    }
}
