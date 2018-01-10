<?php

return [
    'connections' => [

        # Conexão padrão. É onde está rodando toda a aplicação wSGI
        'default' => [
            'driver'    => env('DB_TYPE'),
            'host'      => env('DB_HOST'),
            'database'  => env('DB_BASE'),
            'username'  => env('DB_USER'),
            'password'  => env('DB_PASS'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ],

        # Conexão secundária. Onde está rodando o servidor de Autenticação da aplicação.
        'auth' => [
            'driver'    => env('AU_TYPE'),
            'host'      => env('AU_HOST'),
            'database'  => env('AU_BASE'),
            'username'  => env('AU_USER'),
            'password'  => env('AU_PASS'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ]
    ]
];