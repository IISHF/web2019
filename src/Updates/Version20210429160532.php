<?php

declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429160532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Extends hall of fame with optional second and third place columns';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE hall_of_fame ADD second_place_club VARCHAR(128) DEFAULT NULL, ADD second_place_country VARCHAR(2) DEFAULT NULL, ADD third_place_club VARCHAR(128) DEFAULT NULL, ADD third_place_country VARCHAR(2) DEFAULT NULL'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE hall_of_fame DROP second_place_club, DROP second_place_country, DROP third_place_club, DROP third_place_country'
        );
    }
}
