<?php

namespace App\Http\Controllers;

use App\Helpers\Engine;

class FooController extends Controller
{

    public function index(\Betalabs\Engine\Requests\Request $engineRequest)
    {
        // First we need to add the Tenant in the scope. By default it is not
        // automatic but you can include it in a middleware, for example. Also
        // since we are using auth:api so an user is surely logged.
        Engine::auth(\Auth::user());

        $engineRequest->get('engine-endpoint')->getContents();
        //...

        return response()->setStatusCode(204);
    }

}
