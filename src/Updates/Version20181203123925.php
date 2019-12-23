<?php declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
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
        $table->addColumn('token', 'string', ['length' => 64]);
        $table->addColumn('username', 'string', ['length' => 128]);
        $table->addColumn('sig_key', 'string', ['length' => 32]);
        $table->addColumn('user_ip', 'string', ['length' => 64]);
        $table->addColumn('user_agent', 'string', ['length' => 255]);
        $table->addColumn('ttl', 'datetime');
        $table->addColumn('created', 'datetime');
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
