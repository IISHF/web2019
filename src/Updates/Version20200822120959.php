<?php

declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822120959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds staff member profile image association';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE staff_members ADD image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql(
            'ALTER TABLE staff_members ADD CONSTRAINT FK_1916C5493DA5256D FOREIGN KEY (image_id) REFERENCES files (id) ON DELETE SET NULL'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1916C5493DA5256D ON staff_members (image_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE staff_members DROP FOREIGN KEY FK_1916C5493DA5256D');
        $this->addSql('DROP INDEX UNIQ_1916C5493DA5256D ON staff_members');
        $this->addSql('ALTER TABLE staff_members DROP image_id');
    }
}
