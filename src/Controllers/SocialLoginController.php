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
        $name = $socialUser->getName();
        $email = $socialUser->getEmail();
        if (!$user = $this->model::where('email', $email)->first()) {
            event(new Registered($user = $this->model::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(str_random(32)),
            ])));
            $this->guard()->login($user);
            return redirect()->intended($this->redirectPath());
        }
        Auth::loginUsingId($user->id);
        return redirect()->intended($this->redirectPath());
    }
}
