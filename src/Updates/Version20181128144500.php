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
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('rememberme_token');
        $table->addColumn('series', Type::STRING, ['length' => 88, 'fixed' => true]);
        $table->addColumn('value', Type::STRING, ['length' => 88, 'fixed' => true]);
        $table->addColumn('lastUsed', Type::DATETIME);
        $table->addColumn('class', Type::STRING, ['length' => 100]);
        $table->addColumn('username', Type::STRING, ['length' => 200]);
        $table->setPrimaryKey(['series']);

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('rememberme_token');
    }
}
