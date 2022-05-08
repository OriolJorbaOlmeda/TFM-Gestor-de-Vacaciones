<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220508090852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD name VARCHAR(180) NOT NULL, ADD lastname VARCHAR(180) NOT NULL, ADD direction VARCHAR(180) NOT NULL, ADD city VARCHAR(180) NOT NULL, ADD province VARCHAR(180) NOT NULL, ADD postalcode VARCHAR(180) NOT NULL, ADD total_vacation_days INT NOT NULL, ADD pending_vacation_days INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP name, DROP lastname, DROP direction, DROP city, DROP province, DROP postalcode, DROP total_vacation_days, DROP pending_vacation_days');
    }
}
