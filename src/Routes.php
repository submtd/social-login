<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('login/social/{provider}', '\Submtd\SocialLogin\Controllers\SocialLoginController@redirectToProvider');
    Route::get('login/social/{provider}/callback', '\Submtd\SocialLogin\Controllers\SocialLoginController@handleProviderCallback');
});
