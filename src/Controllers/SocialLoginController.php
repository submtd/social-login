<?php

namespace Submtd\SocialLogin\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class SocialLoginController extends Controller
{
    use RegistersUsers;

    protected $model;

    public function __construct()
    {
        $this->model = config('auth.providers.users.model');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        if (!$socialUser->getEmail()) {
            $user = $this->findOrCreateByProviderId($socialUser, $provider);
        } else {
            $user = $this->findOrCreateByEmail($socialUser, $provider);
        }
        Auth::login($user);
        return redirect()->intended($this->redirectPath());
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
        $user->email = $socialUser->getEmail();
        $user->password = Hash::make(str_random(32));
        $user->provider = $provider;
        $user->provider_id = $socialUser->getId();
        $user->save();
        event(new Registered($user));
        return $user;
    }
}
