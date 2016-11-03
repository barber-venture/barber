<?php use Cake\Core\Configure;

return [
    'HybridAuth' => [
        "base_url" => "http://localhost/hybridauth-git/hybridauth/",
        'providers' => [
            'Google' => [
                'enabled' => true,
                'keys' => [
                    'id' => '<google-client-id>',
                    'secret' => '<secret-key>'
                ]
            ],
            'Facebook' => [
                'enabled' => true,
                'keys' => [
                    'id' => '<facebook-application-id>',
                    'secret' => '<secret-key>'
                ],
                'scope' => 'email, user_about_me, user_birthday, user_hometown'
            ],
            'Twitter' => [
                'enabled' => true,
                'keys' => [
                    'key' => 'MMsWJvcL4Fjvr825L2DFN5PNi',
                    'secret' => 'Wmp976FL0x7tFqlYUbqOd2AElo7hm7hjKsN0Lmj6LOwzgFZDzU'
                ],
                'includeEmail' => true // Only if your app is whitelisted by Twitter Support
            ]
        ],
        'debug_mode' => Configure::read('debug'),
        'debug_file' => LOGS . 'hybridauth.log',
    ]
];