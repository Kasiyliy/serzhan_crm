<?php
//return [
//    'components' => [
//        'db' => [
//            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=localhost;dbname=taxi_plus',
//            'username' => 'root',
//            'password' => 'root',
//            'charset' => 'utf8',
//        ],
//        'mail' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'viewPath' => '@backend/views/mail/',
//            'useFileTransport' => false,
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.yandex.ru',
//                'username' => 'partner@priceclick.kz',
//                'password' => '112233',
//                'port' => '587',
//                'encryption' => 'tls',
//            ],
//        ],
//    ],
//];

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost:3306;dbname=serzhan_crm',
            'username' => 'usr112233',
            'password' => 'pwd112233',
            'charset' => 'utf8',
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@backend/views/mail/',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'partner@priceclick.kz',
                'password' => '112233',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
    ],
];
