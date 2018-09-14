<?php

namespace App\Listeners\Genesis;

use Betalabs\LaravelHelper\Models\Tenant;

class CreateExtraFields extends Base
{

    /**
     * Handle the event.
     *
     * @param \Betalabs\LaravelHelper\Models\Tenant $tenant
     */
    public function handle(Tenant $tenant)
    {
        // Do not forget to authenticate
        $this->authenticate($tenant);

        // Here you can use API to add extra fields.
    }

}