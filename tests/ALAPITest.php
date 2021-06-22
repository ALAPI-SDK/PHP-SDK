<?php

/*
 * This file is part of the ALAPI-SDK/php-sdk.
 * (c) Alone88 <im@alone88.cn>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ALAPI\Tests;

use ALAPI\Client;
use ALAPI\Exception\ALAPIException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ALAPITest extends TestCase
{
    public function testAlapi()
    {
        $client = new Client(getenv('TOKEN'));

        try {
            $result = $client->setApi('/api/hitokoto')
                ->setParam('type', 'a')
                ->request();
            Assert::assertEquals(200, $result->getCode());
        } catch (ALAPIException $e) {
        }
    }

    public function testUpload()
    {
        $client = new Client(getenv('token'));
        try {
            $result = $client->setApi('/api/image')
                ->setParam('image', 'tests/img.png', true)
                ->setParam('type', 'alapi')
                ->request();
            Assert::assertEquals(200, $result->getCode());
        } catch (ALAPIException $e) {
        }
    }
}
