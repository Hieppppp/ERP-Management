<?php

namespace App\Helpers;

use Illuminate\Routing\Route;

class CustomRoute extends Route
{
    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;
    }
}
