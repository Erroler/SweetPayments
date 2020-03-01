<?php

/*
 * This file is part of Laravel Hashids.
 *
 * (c) Vincent Klaiber <hello@doubledip.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        \App\Models\Sale::class => [
            'salt' => \App\Models\Sale::class.'9e3c6ee4c9dfa46ef691beace9b170e9',
            'length' => 10,
        ],

        \App\Models\Subscription::class => [
            'salt' => \App\Models\Subscription::class.'ed15997e009206a9d0bba5f11891c274',
            'length' => 10,
        ],

        \App\Models\Community::class => [
            'salt' => \App\Models\Subscription::class.'b7153bfbfb1f2b71e28582164c446286',
            'length' => 20,
        ],
    ],

];
