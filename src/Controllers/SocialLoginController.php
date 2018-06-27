<?php

namespace Submtd\SocialLogin\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Submtd\SocialLogin\Models\SocialLoginId;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class SocialLoginController extends Controller
{
    use RegistersUsers;

    /**
     * The user class for your laravel application. Typically App\User()
     * This variable is populated from the value in config/auth.php
     *
     * @var string
     */
    protected $userClass;

    /**
     * Class constructor. Grabs some values from config files and populates
     * the class properties.
     */
    public function __construct()
    {
        $this->userClass = config('auth.providers.users.model');
    }

    /**
     * Redirects the user to the oauth page for the provider.
     *
     * @param string $provider
     * @return void
     */
    public function redirectToProvider($provider)
    {
        session(['redirect' => URL::previous()]);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handles the oauth callback from the provider.
     *
     * @param string $provider
     * @return void
     */
    public function handleProviderCallback($provider)
    {
        try {
            // load the user from the driver
            $socialUser = Socialite::driver($provider)->user();
            // first or new the provider creds
            $socialLoginId = SocialLoginId::firstOrNew([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
            // if the provider creds already match a user, log in and move on
            if ($socialLoginId->user_id) {
                Auth::login($socialLoginId->user);
                return redirect()->intended('login');
            }
            if (!config('social-login.allowSocialRegistration', true) && !Auth::user()) {
                abort(403, 'Registration from social media accounts has been disabled.');
            }
            // if the user isn't already logged in and we can't get an email from the provider, fail
            if (!Auth::user() && !$socialUser->getEmail()) {
                abort(400, 'No email address was found for this user.');
            }
            // get the logged in user or create a new one
            if (!$user = Auth::user()) {
                $user = $this->userClass::firstOrNew(['email' => $socialUser->getEmail()]);
            }
            // check if this is a new user
            if (!$user->created_at) {
                $user->name = $socialUser->getName();
                $user->password = Hash::make(str_random(32));
                $user->save();
                // fire the registered event
                event(new Registered($user));
            }
            // log the user in, update the social login id model and redirect
            Auth::login($user);
            $socialLoginId->user_id = $user->id;
            $socialLoginId->save();
            return Redirect::to(session('redirect'));
            // return redirect()->intended('login');
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}
