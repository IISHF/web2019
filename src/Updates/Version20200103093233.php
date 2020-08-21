<?php

declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200103093233 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Adds NGB logo to NGB table';
    }

    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'ALTER TABLE national_governing_bodies ADD logo_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\''
        );
        $this->addSql(
            'ALTER TABLE national_governing_bodies ADD CONSTRAINT FK_175758BAF98F144A FOREIGN KEY (logo_id) REFERENCES files (id) ON DELETE SET NULL'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_175758BAF98F144A ON national_governing_bodies (logo_id)');
    }

    /**
     * {@inheritDoc}
     */
    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE national_governing_bodies DROP FOREIGN KEY FK_175758BAF98F144A');
        $this->addSql('DROP INDEX UNIQ_175758BAF98F144A ON national_governing_bodies');
        $this->addSql('ALTER TABLE national_governing_bodies DROP logo_id');
    }
}
