<?php

namespace Submtd\SocialLogin\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

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
     * The URL the user should be redirected to after confirming their
     * email address. This is populated from config/email-confirmation.php
     *
     * @var string
     */
    protected $redirectOnSuccess;

    /**
     * The URL the user should be redirected to after a failed confirmation
     * attempt. This is populated from config/email-confirmation.php
     *
     * @var string
     */
    protected $redirectOnFail;

    /**
     * Class constructor. Grabs some values from config files and populates
     * the class properties.
     */
    public function __construct()
    {
        $this->userClass = config('auth.providers.users.model');
        $this->redirectOnSuccess = config('social-login.redirectOnSuccess', '/');
        $this->redirectOnFail = config('social-login.redirectOnFail', '/login');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        if (!$socialUser->getEmail()) {
            session(['socialUser' => $socialUser]);
            return view('social-login::ConfirmEmail');
        }
        $user = $this->findOrCreateByEmail($socialUser, $provider);
        Auth::login($user);
        return redirect($this->redirectOnSuccess);
    }

    public function confirmEmail()
    {
        if (!$socialUser = session('socialUser')) {
            Request::session()->flash('status', config('social-login.statusMessages.invalidUser', 'Invalid user.'));
            return redirect($this->redirectOnFail);
        }
        $socialUser->email = Input::get('email');
        $user = $this->findOrCreateByEmail($socialUser);
        Auth::login($user);
        return redirect($this->redirectOnSuccess);
    }

    private function findOrCreateByEmail($socialUser, $provider)
    {
        if (!$user = $this->model::where('email', $socialUser->getEmail())->first()) {
            $user = $this->makeUser($socialUser, $provider);
        }
        return $user;
    }

    private function findOrCreateByProviderId($socialUser, $provider)
    {
        if (!$user = $this->model::where('provider', $provider)->where('provider_id', $socialUser->getId())->first()) {
            $user = $this->makeUser($socialUser, $provider);
        }
        return $user;
    }

    private function makeUser($socialUser, $provider)
    {
        $user = new $this->model();
        $user->name = $socialUser->getName();
        if (!$user->email = $socialUser->getEmail()) {
            if (!$user->email = $socialUser->email) {
                Request::session()->flash('status', config('social-login.statusMessages.invalidUser', 'Invalid user.'));
                return redirect($this->redirectOnFail);
            }
        }
        $user->password = Hash::make(str_random(32));
        $user->provider = $provider;
        $user->provider_id = $socialUser->getId();
        $user->save();
        event(new Registered($user));
        return $user;
    }
}
