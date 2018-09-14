<?php

namespace App\Helpers;

use Betalabs\Engine\Requests\EndpointResolver;
use Betalabs\Engine\Auth\Token;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class Engine
{
    /**
     * Authenticate tenant in Engine
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $tenant
     */
    public static function auth(Authenticatable $tenant): void
    {
        /** @var \Betalabs\LaravelHelper\Models\Tenant $tenant */
        $registry = $tenant->engineRegistry;
        $endpoint = rtrim($registry->api_base_uri, '/api');

        EndpointResolver::setEndpoint($endpoint);
        resolve(Token::class)->informToken($registry->api_access_token);
    }

    /**
     * Make a valid Engine API URL
     *
     * @param null|string $endpoint
     *
     * @return string
     */
    public static function makeUrl(string $endpoint): string
    {
        /** @var \Betalabs\LaravelHelper\Models\Tenant $registry */
        $registry = Auth::user()->engineRegistry;

        return $registry->api_base_uri . '/' . trim($endpoint, '/');
    }
}