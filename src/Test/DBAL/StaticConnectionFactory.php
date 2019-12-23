<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Test\DBAL;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;

/**
 * Class StaticConnectionFactory
 *
 * @package App\Test\DBAL
 * @see     https://github.com/dmaicher/doctrine-test-bundle
 */
class StaticConnectionFactory extends ConnectionFactory
{
    /**
     * @var ConnectionFactory
     */
    private $innerFactory;

    /**
     * @param ConnectionFactory $innerFactory
     */
    public function __construct(ConnectionFactory $innerFactory)
    {
        parent::__construct([]);
        $this->innerFactory = $innerFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function createConnection(
        array $params,
        Configuration $config = null,
        EventManager $eventManager = null,
        array $mappingTypes = []
    ): Connection {
        // create the original connection to get the used wrapper class + driver
        $connectionOriginalDriver = $this->innerFactory->createConnection(
            $params,
            $config,
            $eventManager,
            $mappingTypes
        );
        // wrapper class can be overridden/customized in params (see Doctrine\DBAL\DriverManager)
        $connectionWrapperClass = get_class($connectionOriginalDriver);
        /** @var Connection $connection */
        $connection = new $connectionWrapperClass(
            $connectionOriginalDriver->getParams(),
            new StaticDriver($connectionOriginalDriver->getDriver(), $connectionOriginalDriver->getDatabasePlatform()),
            $connectionOriginalDriver->getConfiguration(),
            $connectionOriginalDriver->getEventManager()
        );
        if (StaticDriver::isKeepStaticConnections()) {
            // The underlying connection already has a transaction started.
            // Make sure we use savepoints to be able to easily roll-back nested transactions
            if ($connection->getDriver()->getDatabasePlatform()->supportsSavepoints()) {
                $connection->setNestTransactionsWithSavepoints(true);
            }
            // We start a transaction on the connection as well
            // so the internal state ($_transactionNestingLevel) is in sync with the underlying connection.
            $connection->beginTransaction();
        }
        return $connection;
    }
}
