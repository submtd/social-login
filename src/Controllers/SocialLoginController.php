<?php

namespace Submtd\SocialLogin\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;

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
            $user = new $this->model();
            $user->name = $name;
            $user->email = $email;
            return redirect()->route('register', ['name' => $name, 'email' => $email]);
        }
        Auth::loginUsingId($user->id);
        return redirect()->intended($this->redirectPath());
    }
}
