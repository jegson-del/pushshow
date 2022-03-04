<?php

return [
    'flow' => [
        'Withdrawal' => '001',
        'Campaign' => '002',
        'Refund' => '003',
        'Account upgraded' => '004',
        'Campaign reward' => '005',
        'Referrer bonus' => '006',
    ],
    
    'flow_codes' => [
        '001' => 'Withdrawal Request',
        '002' => 'Subscriber campaign payment',
        '003' => 'Refund',
        '004' => 'Account upgraded',
        '005' => 'Campaign paticipation reward',
        '006' => 'Referrer bonus',
    ],

    'stripe' => [
        'stripe_key' => env('STRIPE_KEY'),
        'stripe_secret' => env('STRIPE_SECRET'),
    ]
];