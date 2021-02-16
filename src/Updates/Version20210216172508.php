<?php

declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216172508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds YouTube channel and profile and Vimeo profile';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'ALTER TABLE national_governing_bodies ADD you_tube_channel VARCHAR(128) DEFAULT NULL, ADD you_tube_profile VARCHAR(128) DEFAULT NULL, ADD vimeo_profile VARCHAR(128) DEFAULT NULL'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'ALTER TABLE national_governing_bodies DROP you_tube_channel, DROP you_tube_profile, DROP vimeo_profile'
        );
    }
}
