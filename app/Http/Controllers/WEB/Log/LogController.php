<?php

namespace App\Http\Controllers\WEB\Log;

use App\Http\Controllers\Controller;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * index
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/log/index');
    }
}
