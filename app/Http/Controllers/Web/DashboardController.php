<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\MovementOrder;
use App\Models\Product;
use App\Models\Zone;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalZones'     => Zone::count(),
            'totalLocations' => Location::count(),
            'totalProducts'  => Product::count(),
            'totalStock'     => Inventory::sum('quantity'),
            'pendingOrders'  => MovementOrder::where('status', 'pending')->count(),
            'recentOrders'   => MovementOrder::with(['product', 'destinationLocation'])
                                    ->latest()->take(5)->get(),
            'lowStock'       => Inventory::with(['product', 'location'])
                                    ->where('quantity', '<=', 10)->get(),
        ]);
    }
}
