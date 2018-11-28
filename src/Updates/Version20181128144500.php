<?php declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181128144500 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $rememberMeTokenTable = $schema->createTable('rememberme_token');
        $rememberMeTokenTable->addColumn('series', Type::STRING, ['length' => 88, 'fixed' => true]);
        $rememberMeTokenTable->addColumn('value', Type::STRING, ['length' => 88, 'fixed' => true]);
        $rememberMeTokenTable->addColumn('lastUsed', Type::DATETIME);
        $rememberMeTokenTable->addColumn('class', Type::STRING, ['length' => 100]);
        $rememberMeTokenTable->addColumn('username', Type::STRING, ['length' => 200]);
        $rememberMeTokenTable->setPrimaryKey(['series']);

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('rememberme_token');
    }
}
