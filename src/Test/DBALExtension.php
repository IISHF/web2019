<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Test;

use App\Test\DBAL\StaticDriver;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;
use PHPUnit\Runner\BeforeTestHook;

/**
 * Class DBALExtension
 *
 * @package App\Test
 * @see     https://github.com/dmaicher/doctrine-test-bundle
 */
class DBALExtension implements BeforeFirstTestHook, AfterLastTestHook, BeforeTestHook, AfterTestHook
{
    /**
     * {@inheritDoc}
     */
    public function executeBeforeFirstTest(): void
    {
        StaticDriver::setKeepStaticConnections(true);
    }

    /**
     * {@inheritDoc}
     */
    public function executeBeforeTest(string $test): void
    {
        StaticDriver::beginTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function executeAfterTest(string $test, float $time): void
    {
        StaticDriver::rollBack();
    }

    /**
     * {@inheritDoc}
     */
    public function executeAfterLastTest(): void
    {
        StaticDriver::setKeepStaticConnections(false);
    }
}
