<?php

return [
    /**
     * set redirectOnSuccess to the path the user shold be
     * redirected to after successfully loggin in.
     */
    'redirectOnSuccess' => '/',

    /**
     * set redirectOnFail to the path the user shold be
     * redirected to after a failed login attempt.
     */
    'redirectOnFail' => '/login',

    /**
     * The following status messages are flashed to the 'status' session variable
     * after various SocialLogin events.
     */
    'statusMessages' => [
        /**
         * noEmailFound is the message flashed when the provider doesn't provide
         * an email address for the user.
         */
        'noEmailFound' => 'No email address was found for this user.',
    ]
];
