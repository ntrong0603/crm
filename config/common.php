<?php

return [
    'mail_setting' => [
        'mail_type' => [
            '1' => 'シナリオメール',
            '2' => 'スポットメール',
        ],
    ],
    
    'mail_to_report_review'   => env('MAIL_TO', ''),
    'mail_cc_report_review'   => env('MAIL_TO_CC', ''),
];
