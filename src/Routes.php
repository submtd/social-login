<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('auth/social/callback/{provider}', '\Submtd\SocialLogin\Controllers\SocialLoginController@handleProviderCallback');
    Route::get('auth/social/{provider}', '\Submtd\SocialLogin\Controllers\SocialLoginController@redirectToProvider');
});
