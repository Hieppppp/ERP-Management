<?php

namespace App\Http\Controllers\WEB\DashBoard;

use App\Http\Controllers\Controller;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View|Factory
    {
        return view('pages/home/index');
    }
}
