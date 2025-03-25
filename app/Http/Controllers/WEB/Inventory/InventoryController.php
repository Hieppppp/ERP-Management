<?php

namespace App\Http\Controllers\WEB\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ProductLocation;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * edit
     *
     * @param  int $id
     * @return View|Factory
     */
    public function edit(int $id): View|Factory
    {
        $data['inventory'] = ProductLocation::findOrFail($id);
        return view('pages/inventory/edit', $data);
    }

    /**
     * select inventory view
     *
     * @param  int $id
     * @return View|Factory
     */
    public function select(Request $request): View|Factory
    {
        $productId = '';
        $warehouseId = '';
        if ($request->has('productId')) {
            $productId = $request->query('productId');
        }
        if ($request->has('warehouseId')) {
            $warehouseId = $request->query('warehouseId');
        }
        return view('pages/inventory/select', [
            'productId' => $productId,
            'warehouseId' => $warehouseId
        ]);
    }
}
