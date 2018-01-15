<?php

namespace Submtd\SocialLogin\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLoginId extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
    ];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
