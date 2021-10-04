<?php

/**
 * Guzzle handler
 * @package iqomp/growinc-pga
 * @version 1.0.0
 */

namespace Iqomp\GrowIncPGA;

use GuzzleHttp\Client;

class Guzzle
{
    protected static $client;

    public static function getClient(): Client
    {
        if (self::$client) {
            return self::$client;
        }

        self::$client = new Client([
            'base_uri' => 'https://g-pay.to/api/',
            'headers' => [
                'User-Agent' => 'iqomp/growinc-pga',
                'Accept' => 'application/json',
                'X-Version' => '1.0.0'
            ]
        ]);

        return self::$client;
    }
}
