<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205020327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, INDEX IDX_D4E6F8119EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_amount INT NOT NULL, UNIQUE INDEX UNIQ_BA388B719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total DOUBLE PRECISION NOT NULL, code VARCHAR(255) NOT NULL, payment_method INT NOT NULL, address_used VARCHAR(255) NOT NULL, INDEX IDX_F529939819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_entry (id INT AUTO_INCREMENT NOT NULL, command_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, short_description VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A8BFE98D33E1689A (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, price INT NOT NULL, short_description VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_cart (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_864BAA161AD5CDBF (cart_id), INDEX IDX_864BAA164584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE order_entry ADD CONSTRAINT FK_A8BFE98D33E1689A FOREIGN KEY (command_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA161AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA164584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8119EB6921');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B719EB6921');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939819EB6921');
        $this->addSql('ALTER TABLE order_entry DROP FOREIGN KEY FK_A8BFE98D33E1689A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA161AD5CDBF');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA164584665A');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_entry');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_cart');
    }
}
