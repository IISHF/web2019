<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Test\DBAL;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Statement;
use PDO;

/**
 * Class StaticConnection
 *
 * @package App\Test\DBAL
 * @see     https://github.com/dmaicher/doctrine-test-bundle
 */
class StaticConnection implements Connection
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var bool
     */
    private $transactionStarted = false;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function prepare($prepareString): Statement
    {
        return $this->connection->prepare($prepareString);
    }

    /**
     * {@inheritDoc}
     */
    public function query(): Statement
    {
        return call_user_func_array([$this->connection, 'query'], func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function quote($input, $type = PDO::PARAM_STR)
    {
        return $this->connection->quote($input, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function exec($statement): int
    {
        return $this->connection->exec($statement);
    }

    /**
     * {@inheritDoc}
     */
    public function lastInsertId($name = null): string
    {
        return $this->connection->lastInsertId($name);
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction(): bool
    {
        if ($this->transactionStarted) {
            return $this->connection->beginTransaction();
        }
        return $this->transactionStarted = true;
    }

    /**
     * {@inheritDoc}
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * {@inheritDoc}
     */
    public function errorCode(): ?string
    {
        return $this->connection->errorCode();
    }

    /**
     * {@inheritDoc}
     */
    public function errorInfo(): array
    {
        return $this->connection->errorInfo();
    }

    /**
     * @return Connection
     */
    public function getWrappedConnection(): Connection
    {
        return $this->connection;
    }
}
