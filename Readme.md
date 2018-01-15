# Social Login for Laravel

This package enables login and registration for users via social media in your Laravel app. This package requires no changes to core laravel files or your user model.

## Installation

Begin by pulling in the package through Composer.

```bash
composer require submtd/social-login
```

Next, if you are using Laravel 5.4, you will need to include the service provider in your `config/app.php` file. If you are using Laravel 5.5, these will be discovered automatically.

```php
'providers' => [
    Submtd\SocialLogin\Providers\SocialLoginServiceProvider::class,
];
```

Next, you will need to run the migrations in order to create the `social_login_ids` table.

```bash
php artisan migrate
```

Next, in order to see the status messages generated by this package, you will need to add the following code to your applications main blade template file.

```html
@if(session('status'))
    <div class="alert">
        {{ session('status') }}
    </div>
@endif
```

Finally, some links will need to be created for each provider you would like to use.

```html
<a href="/auth/social/bitbucket">Log in with Bitbucket</a>
<a href="/auth/social/facebook">Log in with Facebook</a>
<a href="/auth/social/github">Log in with Github</a>
<a href="/auth/social/google">Log in with Google</a>
<a href="/auth/social/twitter">Log in with Twitter</a>
```

## Configuration

If you would like to edit the configuration, you must run the following artisan command to copy the config file to your app directory.

```bash
php artisan vendor:publish --provider="Submtd\SocialLogin\Providers\SocialLoginServiceProvider"
```

After running this command, the status messages can be found in `config/social-login.php`.

If you would like to only publish the config or the migrations, use the `--tag` option on the artisan command.

```bash
php artisan vendor:publish --provider="Submtd\SocialLogin\Providers\SocialLoginServiceProvider" --tag=config
php artisan vendor:publish --provider="Submtd\SocialLogin\Providers\SocialLoginServiceProvider" --tag=migrations
```

## Configuring Authentication Providers

Authentication provider configuration is stored in `config/services.php`. More information on configuring the providers can be found on the [Laravel Socialite Documentation](https://laravel.com/docs/5.5/socialite#configuration) page.

## Donation

If this project helped you save some development time, feel free to buy me a beer ;)

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G72FZ5PYP6EZU)
