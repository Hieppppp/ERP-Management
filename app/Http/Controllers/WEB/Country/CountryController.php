<?php

namespace App\Http\Controllers\WEB\Country;

use App\Http\Controllers\Controller;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;

class CountryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/country/create');
    }
}
