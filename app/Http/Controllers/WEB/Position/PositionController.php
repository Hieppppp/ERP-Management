<?php

namespace App\Http\Controllers\WEB\Position;

use App\Http\Controllers\Controller;
use App\Services\Position\PositionServiceInterface;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected PositionServiceInterface $positionService;

    public function __construct(
        PositionServiceInterface $positionService
    )
    {
        $this->positionService = $positionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages/position/index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages/position/create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
