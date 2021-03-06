<?php

/**
 * SocialLogin routes
 */
Route::group(['middleware' => ['web']], function () {
    // handle callbacks from the providers
    Route::get('auth/social/callback/{provider}', '\Submtd\SocialLogin\Controllers\SocialLoginController@handleProviderCallback')->name('social.callback');
    // redirects the user to the provider
    Route::get('auth/social/{provider}', '\Submtd\SocialLogin\Controllers\SocialLoginController@redirectToProvider')->name('social.redirect');
});
