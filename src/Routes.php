<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('auth/social/callback/{provider}', '\Submtd\SocialLogin\Controllers\SocialLoginController@handleProviderCallback');
    Route::post('auth/social/confirm-email', '\Submtd\SocialLogin\Controllers\SocialLoginController@confirmEmail');
    Route::get('auth/social/{provider}', '\Submtd\SocialLogin\Controllers\SocialLoginController@redirectToProvider');
});
