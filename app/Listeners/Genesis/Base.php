<?php

namespace App\Listeners\Genesis;

use App\Helpers\Engine;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Passport;

abstract class Base
{

    /**
     * Authenticate tenant
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $authenticatable
     */
    protected function authenticate(Authenticatable $authenticatable)
    {
        Passport::actingAs($authenticatable);
        Engine::auth($authenticatable);
    }

}