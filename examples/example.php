<?php

/*
 * This file is part of the ALAPI-SDK/php-sdk.
 * (c) Alone88 <im@alone88.cn>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use ALAPI\Client;
use ALAPI\Exception\ALAPIException;

require_once 'vendor/autoload.php';

$client = new Client('你的token');

try {
    $result = $client->setApi('/api/url')
        ->setParam('url', 'https://www.alapi.cn')
        ->throw()
        ->setParam('type', 'tcn')
        ->request();
} catch (ALAPIException $e) {
}

var_dump($result->getData());
