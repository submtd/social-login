<?php

return [
    /**
     * Set the following to true to allow users to register a new account
     * with social media or false to disable it.
     */
    'allowSocialRegistration' => true,

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

        /**
         * socialRegistrationDisabled is the message flashed when a user tries to
         * register with a social media account, but the allowSocialRegistration config
         * setting is set to false.
         */
        'socialRegistrationDisabled' => 'Registration from social media accounts has been disabled.',
    ]
];
