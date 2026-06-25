<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::with(['product', 'location.zone'])
            ->orderByDesc('quantity')
            ->get();

        return view('inventory.index', compact('inventory'));
    }
}
