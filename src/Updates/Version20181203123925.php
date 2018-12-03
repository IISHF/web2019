<?php declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203123925 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('login_tokens');
        $table->addColumn('token', Type::STRING, ['length' => 64]);
        $table->addColumn('username', Type::STRING, ['length' => 128]);
        $table->addColumn('sig_key', Type::STRING, ['length' => 32]);
        $table->addColumn('user_ip', Type::STRING, ['length' => 64]);
        $table->addColumn('user_agent', Type::STRING, ['length' => 255]);
        $table->addColumn('ttl', Type::DATETIME);
        $table->addColumn('created', Type::DATETIME);
        $table->setPrimaryKey(['token']);
        $table->addUniqueIndex(['username']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('login_tokens');
    }
}
