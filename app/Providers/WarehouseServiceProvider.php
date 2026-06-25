<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Warehouse\InventoryRepositoryInterface;
use App\Repositories\EloquentInventoryRepository;
use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            InventoryRepositoryInterface::class,
            EloquentInventoryRepository::class,
        );
    }
}
