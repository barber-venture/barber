<?php

return [
    'Site' => [
        'title' => '::BARBER::',
        'CakeDateFormat' => 'Y-M-d',
        'DatePickerFormat' => 'mm/dd/yyyy',
        'CakeDateFormatForView' => 'm/d/Y',
        'CakeDateTimeFormatForView' => 'm/d/Y H:i A',
        'ReduceMaxProjectAmountBy' => 0.9,
        'AdminImages' => WWW_ROOT . 'uploads' . DS . 'sites' . DS,
        'ProfileImage' => WWW_ROOT . 'uploads' . DS . 'users' . DS,
        'attachment' => WWW_ROOT . 'uploads' . DS . 'attachment' . DS,
        'image_upload_limit_for_normal_user' => '5',
		'googlemap_key' => '',		 
		'support_email' => 'ms9785299022@gmail.com'
		
    ],
    'Facebook' => [
        'appId' => '',
        'secret' => ''
    ],
    'Paypal' => [
        'paypal_username' => '',
        'paypal_password' => '',
        'paypal_signature' => '',
        'paypal_oAuthClientId' => '',
        'paypal_oAuthSecret' => ''
    ],
    'Twitter' => [
        'appId' => '',
        'secret' => '',
        'callback' => ''
    ],
    'SiteSetting' => [
        'titles' => ['Mr' => 'Mr', 'Mrs' => 'Mrs', 'Miss' => 'Miss'],
        'OptionYesNo' => ['1' => 'Yes', '0' => 'No'],
        'IsOnline' => ['1' => 'Yes', '2' => 'No'],
        'limit' => ['5' => '5', '10' => '10', '15' => '15', '20' => '20', '25' => '25'],
        'UserType' => ['1' => 'Barber User', '2' => 'Normal User'],
        'Gender' => ['1' => 'Male', '2' => 'Female'],
        'Status' => ['1' => 'Active', '0' => 'Detactive'],
       
    ], 
    
    'ACL' => [
        1 => [//Role Id For Admin
            'Controller' => [
                'Users' => [
                    'DenyAction' => [
                        'Test',
                         
                    ]
                ],
                'Projects' => [
                    'DenyAction' => [
                        '*'
                    ]
                ]
            ]
        ],
        2 => [//Role Id For Pace Manager
            'Controller' => [
                'Users' => [
                    'DenyAction' => [
                    ]
                ],
                'Projects' => [
                    'DenyAction' => [
                    ]
                ]
            ]
        ],
        3 => [//Role Id For Contractor
            'Controller' => [
                'Users' => [
                    'DenyAction' => [
                        'teter',
                        
                    ]
                ],
                'Projects' => [
                    'DenyAction' => [
                        'verifyDocuments'
                    ]
                ]
            ]
        ],
        4 => [//Role Id For Salesperson
            'Controller' => [
                'Users' => [
                    'DenyAction' => [
                        'asdsad',
                         
                    ]
                ],
                'Projects' => [
                    'DenyAction' => [
                        'asds',
                       
                    ]
                ]
            ]
        ],
       
    ],
    
     'twilio' => [
        'sid' =>'', 
        'token' =>'', 
        'fromNumber' =>'',     
    ],
];
