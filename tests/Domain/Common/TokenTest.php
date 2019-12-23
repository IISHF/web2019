<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Tests\Domain\Common;

use App\Domain\Common\Token;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenTest
 *
 * @package App\Tests\Domain\Common
 */
class TokenTest extends TestCase
{
    public function testRandomRaw(): void
    {
        $token = Token::random(10, true);
        self::assertEquals(10, strlen($token));
    }

    public function testRandom(): void
    {
        $token = Token::random(10, false);
        self::assertEquals(20, strlen($token));
        self::assertRegExp('/^[0-9a-f]{20}$/', $token);
    }

    public function testHashFromRawToRaw(): void
    {
        $token = Token::random(10, true);
        $hash  = Token::hash($token, true, true);
        self::assertEquals(32, strlen($hash));
    }

    public function testHashFromRawToHex(): void
    {
        $token = Token::random(10, true);
        $hash  = Token::hash($token, true, false);
        self::assertEquals(64, strlen($hash));
        self::assertRegExp('/^[0-9a-f]{64}$/', $hash);
    }

    public function testHashFromHexToHex(): void
    {
        $token = Token::random(10, false);
        $hash  = Token::hash($token, false, false);
        self::assertEquals(64, strlen($hash));
        self::assertRegExp('/^[0-9a-f]{64}$/', $hash);
    }

    public function testHashFromHexToRaw(): void
    {
        $token = Token::random(10, false);
        $hash  = Token::hash($token, false, true);
        self::assertEquals(32, strlen($hash));
    }

    public function testHexToBinary(): void
    {
        $token  = Token::random(10, false);
        $binary = Token::binary($token);
        self::assertEquals(10, strlen($binary));
    }

    public function testSomethingToBinary(): void
    {
        $binary = Token::binary('thisisnotavalidhexrepresentation');
        self::assertEquals(0, strlen($binary));
    }

    public function testBinaryToHex(): void
    {
        $token  = Token::random(10, true);
        $binary = Token::hex($token);
        self::assertEquals(20, strlen($binary));
    }

    public function testSomeThingToHex(): void
    {
        $binary = Token::hex('thisisjustaplainstringwith38characters');
        self::assertEquals(76, strlen($binary));
    }
}
