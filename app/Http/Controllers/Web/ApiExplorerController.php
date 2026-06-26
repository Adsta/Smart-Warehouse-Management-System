<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\MovementOrder;
use App\Models\Product;

class ApiExplorerController extends Controller
{
    public function index()
    {
        return view('api-explorer', [
            'products'       => Product::orderBy('name')->get(),
            'locations'      => Location::with('zone')->where('is_active', true)->get(),
            'recentOrders'   => MovementOrder::with(['product', 'sourceLocation', 'destinationLocation'])
                                    ->latest()->take(20)->get(),
            'csrfToken'      => csrf_token(),
        ]);
    }
}
