<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS "From" Number
    |--------------------------------------------------------------------------
    |
    | This configuration option defines the phone number that will be used as
    | the "from" number for all outgoing text messages. You should provide
    | the number you have already reserved within your LabsMobile dashboard.
    |
    */

    'sms_from' => env('LABSMOBILE_SMS_FROM'),

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | The following configuration options contain your API credentials, which
    | may be accessed from your LabsMobile dashboard. These credentials may be
    | used to authenticate with the LabsMobile API so you may send messages.
    |
    */

    'username' => env('LABSMOBILE_USERNAME'),

    'password' => env('LABSMOBILE_PASSWORD'),
];
