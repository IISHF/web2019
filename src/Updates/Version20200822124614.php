<?php

declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822124614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds committee member profile image association';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE committee_members ADD image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql(
            'ALTER TABLE committee_members ADD CONSTRAINT FK_4E0D736F3DA5256D FOREIGN KEY (image_id) REFERENCES files (id) ON DELETE SET NULL'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4E0D736F3DA5256D ON committee_members (image_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE committee_members DROP FOREIGN KEY FK_4E0D736F3DA5256D');
        $this->addSql('DROP INDEX UNIQ_4E0D736F3DA5256D ON committee_members');
        $this->addSql('ALTER TABLE committee_members DROP image_id');
    }
}
