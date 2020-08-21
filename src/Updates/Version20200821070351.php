<?php

declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821070351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds Tik Tok and Telegram profile columns to NGB table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'ALTER TABLE national_governing_bodies ADD tik_tok_profile VARCHAR(128) DEFAULT NULL, ADD telegram_profile VARCHAR(128) DEFAULT NULL'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE national_governing_bodies DROP tik_tok_profile, DROP telegram_profile');
    }
}
