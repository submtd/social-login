<?php

namespace Submtd\SocialLogin\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

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
        $user = $this->findOrCreate($socialUser, $provider);
        Auth::login($user);
        return redirect()->intended($this->redirectPath());
    }

    private function findOrCreate($socialUser, $provider)
    {
        if (!$user = $this->model::where('provider', $provider)->where('provider_id', $socialUser->getId())->first()) {
            $user = new $this->model();
            $user->name = $socialUser->getName();
            $user->email = $socialUser->getEmail();
            $user->provider = $provider;
            $user->provider_id = $socialUser->getId();
            $user->save();
            event(new Registered($user));
        }
        return $user;
    }
}
