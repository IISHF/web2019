<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Test\DBAL;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\DBAL\Driver\ExceptionConverterDriver;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\VersionAwarePlatformDriver;
use PDOException;

/**
 * Class StaticDriver
 *
 * @package App\Test\DBAL
 * @see     https://github.com/dmaicher/doctrine-test-bundle
 */
class StaticDriver implements Driver, ExceptionConverterDriver, VersionAwarePlatformDriver
{
    /**
     * @var Connection[]
     */
    private static $connections = [];

    /**
     * @var bool
     */
    private static $keepStaticConnections = false;

    /**
     * @var Driver
     */
    private $innerDriver;

    /**
     * @var AbstractPlatform
     */
    private $platform;

    /**
     * @param Driver           $innerDriver
     * @param AbstractPlatform $platform
     */
    public function __construct(Driver $innerDriver, AbstractPlatform $platform)
    {
        $this->innerDriver = $innerDriver;
        $this->platform    = $platform;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = []): Connection
    {
        if (self::$keepStaticConnections) {
            $key = sha1(serialize($params) . $username . $password);
            if (!isset(self::$connections[$key])) {
                self::$connections[$key] = $this->innerDriver->connect(
                    $params,
                    $username,
                    $password,
                    $driverOptions
                );
                self::$connections[$key]->beginTransaction();
            }
            return new StaticConnection(self::$connections[$key]);
        }
        return $this->innerDriver->connect($params, $username, $password, $driverOptions);
    }

    /**
     * {@inheritDoc}
     */
    public function getDatabasePlatform(): AbstractPlatform
    {
        return $this->platform;
    }

    /**
     * {@inheritDoc}
     */
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn): AbstractSchemaManager
    {
        return $this->innerDriver->getSchemaManager($conn);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->innerDriver->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getDatabase(\Doctrine\DBAL\Connection $conn): string
    {
        return $this->innerDriver->getDatabase($conn);
    }

    /**
     * {@inheritDoc}
     */
    public function convertException($message, DriverException $exception): Exception\DriverException
    {
        if ($this->innerDriver instanceof ExceptionConverterDriver) {
            return $this->innerDriver->convertException($message, $exception);
        }
        return new Exception\DriverException($message, $exception);
    }

    /**
     * {@inheritDoc}
     */
    public function createDatabasePlatformForVersion($version): AbstractPlatform
    {
        return $this->platform;
    }

    /**
     * @param bool $keepStaticConnections
     */
    public static function setKeepStaticConnections(bool $keepStaticConnections): void
    {
        self::$keepStaticConnections = $keepStaticConnections;
    }

    /**
     * @return bool
     */
    public static function isKeepStaticConnections(): bool
    {
        return self::$keepStaticConnections;
    }

    /**
     *
     */
    public static function beginTransaction(): void
    {
        foreach (self::$connections as $con) {
            try {
                $con->beginTransaction();
            } catch (PDOException $e) {
                //transaction could be started already
            }
        }
    }

    /**
     *
     */
    public static function rollBack(): void
    {
        foreach (self::$connections as $con) {
            $con->rollBack();
        }
    }

    /**
     *
     */
    public static function commit(): void
    {
        foreach (self::$connections as $con) {
            $con->commit();
        }
    }
}
