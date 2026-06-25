# 🏭 Smart Warehouse Management System

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)

A production-ready warehouse management backend and UI built with **strict OOP**, **SOLID principles**, and **Domain-Driven Design (DDD)** patterns on Laravel 11. Manage zones, locations, products, inventory, and movement orders through a clean web dashboard or a RESTful JSON API.

---

## ✨ Features

- **Dashboard** — real-time stats, recent orders, low-stock alerts, and quick actions
- **Zone Management** — create and manage warehouse zones (standard, cold storage, hazmat, inbound, outbound)
- **Location Management** — rack locations with 3D spatial coordinates (X, Y, Z) and capacity limits
- **Product Catalogue** — full CRUD with weight, volume, cold-chain, and hazmat flags
- **Inventory Tracking** — live view of quantity, reserved stock, and available stock per location
- **Movement Orders** — inbound receipts and inter-location transfers with full lifecycle (pending → completed / cancelled)
- **Smart Putaway Strategies** — automatically selects the nearest location or a cold-storage-only zone based on product type
- **RESTful JSON API** — all operations also exposed as API endpoints
- **Pessimistic Locking** — `SELECT FOR UPDATE` prevents race conditions during concurrent stock operations

---

## 🏗️ Architecture & Design Patterns

This project is intentionally built to demonstrate enterprise-grade OOP and DDD patterns:

| Pattern | Implementation |
|---|---|
| **Value Objects** | `SpatialCoordinate`, `PhysicalDimension` — immutable, self-validating |
| **Laravel Custom Casts** | Value objects hydrated automatically from Eloquent models |
| **Strategy Pattern** | `NearestLocationStrategy`, `ColdChainStrategy` implement `PutawayStrategyInterface` |
| **Repository Pattern** | `EloquentInventoryRepository` implements `InventoryRepositoryInterface` |
| **DTO (Data Transfer Object)** | `MovementOrderDTO` — PHP 8.2 `readonly` properties, validated at boundary |
| **Service Layer** | `PutawayService`, `MovementOrderService` — orchestrate domain logic |
| **IoC / Dependency Injection** | `WarehouseServiceProvider` binds interfaces to implementations |

---

## 🧰 Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.4 |
| Framework | Laravel 11 |
| Database | SQLite (file-based, zero config) |
| Frontend | Blade + Tailwind CSS (CDN) + Alpine.js (CDN) |
| API | Laravel HTTP routing + JSON responses |

---

## 📁 Project Structure

```
app/
├── Casts/
│   ├── PhysicalDimensionCast.php      # Hydrates weight+volume → PhysicalDimension VO
│   └── SpatialCoordinateCast.php      # Hydrates x+y+z → SpatialCoordinate VO
├── Contracts/
│   └── Warehouse/
│       ├── InventoryRepositoryInterface.php
│       └── PutawayStrategyInterface.php
├── DTOs/
│   └── MovementOrderDTO.php           # Immutable readonly DTO with validation
├── Http/Controllers/
│   ├── MovementOrderController.php    # API controller
│   └── Web/                           # Web UI controllers
│       ├── DashboardController.php
│       ├── InventoryController.php
│       ├── LocationController.php
│       ├── MovementOrderWebController.php
│       ├── ProductController.php
│       └── ZoneController.php
├── Models/
│   ├── Inventory.php
│   ├── Location.php                   # Uses SpatialCoordinateCast
│   ├── MovementOrder.php
│   ├── Product.php                    # Uses PhysicalDimensionCast
│   └── Zone.php
├── Providers/
│   └── WarehouseServiceProvider.php   # Binds interfaces → implementations
├── Repositories/
│   └── EloquentInventoryRepository.php
├── Services/Warehouse/
│   ├── MovementOrderService.php
│   ├── PutawayService.php
│   └── Strategies/
│       ├── ColdChainStrategy.php      # Cold storage only
│       └── NearestLocationStrategy.php # Shortest Euclidean distance
└── ValueObjects/
    ├── PhysicalDimension.php
    └── SpatialCoordinate.php

database/migrations/
├── 2024_01_01_000001_create_zones_table.php
├── 2024_01_01_000002_create_locations_table.php
├── 2024_01_01_000003_create_products_table.php
├── 2024_01_01_000004_create_inventories_table.php
└── 2024_01_01_000005_create_movement_orders_table.php

resources/views/
├── layouts/app.blade.php              # Shared sidebar + layout
├── dashboard.blade.php
├── inventory/index.blade.php
├── locations/{index,create,edit}.blade.php
├── movement-orders/{index,create}.blade.php
├── products/{index,create,edit}.blade.php
└── zones/{index,create,edit}.blade.php
```

---

## ✅ Prerequisites

Make sure the following are installed on your machine before proceeding:

| Requirement | Minimum Version | Check command |
|---|---|---|
| PHP | 8.2+ | `php --version` |
| Composer | 2.x | `composer --version` |

> **No database server needed.** This project uses SQLite, which is a single file bundled with PHP.

---

## 🚀 Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/smart-warehouse.git
cd smart-warehouse

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create the SQLite database file
touch database/database.sqlite

# 6. Run all migrations
php artisan migrate
```

> **Windows users:** Replace `touch database/database.sqlite` with:
> ```powershell
> New-Item database/database.sqlite -ItemType File
> ```

---

## 🔐 Environment Variables

Copy `.env.example` to `.env`. The defaults work out of the box with SQLite.

```env
APP_NAME="Smart Warehouse"
APP_ENV=local
APP_KEY=           # Auto-generated by php artisan key:generate
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# SQLite — no changes needed
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

> For production, set `APP_ENV=production`, `APP_DEBUG=false`, and configure a proper database (MySQL / PostgreSQL) in `.env`.

---

## ▶️ Running the Project (Development)

**Windows (PowerShell) — run each command separately:**

```powershell
cd "path\to\smart-warehouse"
php artisan serve
```

**macOS / Linux:**

```bash
cd path/to/smart-warehouse && php artisan serve
```

Open your browser at **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

The server must remain running while you use the app. To stop it, press `Ctrl + C`.

> To use a different port: `php artisan serve --port=8080`

---

## 🗄️ Database Setup & Migration

```bash
# Run all pending migrations
php artisan migrate

# Wipe the database and start fresh
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

### Database Schema

| Table | Key Columns |
|---|---|
| `zones` | `id`, `name`, `code`, `type` (standard / cold_storage / hazmat / inbound / outbound) |
| `locations` | `id`, `zone_id`, `code`, `x_coord`, `y_coord`, `z_coord`, `max_weight`, `max_volume`, `is_active` |
| `products` | `id`, `sku`, `name`, `weight`, `volume`, `requires_cold_storage`, `is_hazmat` |
| `inventories` | `id`, `product_id`, `location_id`, `quantity`, `reserved_quantity` |
| `movement_orders` | `id`, `reference_number`, `product_id`, `source_location_id`, `destination_location_id`, `quantity`, `type`, `status`, `created_by`, `completed_at` |

---

## 🌐 Web UI Pages

| URL | Description |
|---|---|
| `/dashboard` | Overview stats, recent orders, low-stock alerts |
| `/zones` | List, create, edit, delete zones |
| `/locations` | List, create, edit, delete rack locations |
| `/products` | List, create, edit, delete products |
| `/inventory` | Read-only stock level view |
| `/movement-orders` | List all orders; complete or cancel pending ones |
| `/movement-orders/create` | Create a new inbound or transfer order |

---

## 📡 API Documentation

Base URL: `http://127.0.0.1:8000/api`

All API responses are JSON. Send `Content-Type: application/json` on POST requests.

### Endpoints

| Method | URL | Description |
|---|---|---|
| `POST` | `/api/movement-orders` | Create an inbound or transfer order |
| `PATCH` | `/api/movement-orders/{id}/complete` | Complete a pending transfer order |

---

### `POST /api/movement-orders`

**Request body:**

| Field | Type | Required | Notes |
|---|---|---|---|
| `product_id` | integer | ✅ | Must exist in `products` table |
| `destination_location_id` | integer | ✅ | Must exist in `locations` table |
| `quantity` | integer | ✅ | Minimum: 1 |
| `type` | string | ✅ | `inbound` or `transfer` |
| `source_location_id` | integer | ❌ | Required when `type = transfer` |
| `notes` | string | ❌ | Max 1000 characters |

**Example — Inbound shipment:**
```json
{
    "product_id": 1,
    "destination_location_id": 1,
    "quantity": 50,
    "type": "inbound"
}
```

**Example — Transfer between locations:**
```json
{
    "product_id": 1,
    "source_location_id": 1,
    "destination_location_id": 2,
    "quantity": 10,
    "type": "transfer"
}
```

**Success `201`:**
```json
{
    "id": 1,
    "reference_number": "MO-HJIFVLD3MC",
    "product_id": 1,
    "status": "completed",
    "quantity": 50,
    "type": "inbound",
    "created_at": "2026-06-25T13:28:19.000000Z"
}
```

---

### `PATCH /api/movement-orders/{id}/complete`

Marks a pending transfer as completed and moves stock from source to destination atomically.

**Success `200`:** Returns the updated order object.

---

## 🧪 Testing

Use Laravel Tinker for interactive live testing:

```bash
php artisan tinker
```

**Seed test data:**
```php
$zone     = App\Models\Zone::create(['name' => 'Rack A', 'code' => 'RACK-A', 'type' => 'standard']);
$location = App\Models\Location::create(['zone_id' => $zone->id, 'code' => 'A-01-01', 'x_coord' => 3, 'y_coord' => 4, 'z_coord' => 0, 'max_weight' => 500, 'max_volume' => 10, 'is_active' => true]);
$product  = App\Models\Product::create(['sku' => 'SKU-001', 'name' => 'Widget A', 'weight' => 2.5, 'volume' => 0.5]);
$user     = App\Models\User::factory()->create();
```

**Test Value Objects:**
```php
$a = new App\ValueObjects\SpatialCoordinate(0, 0, 0);
$b = new App\ValueObjects\SpatialCoordinate(3, 4, 0);
echo $a->calculateEuclideanDistance($b); // → 5.0

$d = new App\ValueObjects\PhysicalDimension(10.5, 2.3);
echo $d->fitsWithin(new App\ValueObjects\PhysicalDimension(20, 5)) ? 'fits' : 'no fit'; // → fits
```

**Test the API from Tinker:**
```php
$r = Illuminate\Support\Facades\Http::post('http://127.0.0.1:8000/api/movement-orders', [
    'product_id' => 1, 'destination_location_id' => 1, 'quantity' => 10, 'type' => 'inbound'
]);
echo $r->body();
```

---

## 🛠️ Available Commands

```bash
php artisan serve                   # Start development server
php artisan migrate                 # Run pending migrations
php artisan migrate:fresh           # Wipe DB and re-migrate
php artisan migrate:status          # Show migration status
php artisan tinker                  # Interactive PHP REPL
php artisan route:list              # List all registered routes
php artisan config:clear            # Clear config cache
php artisan cache:clear             # Clear application cache
php artisan optimize:clear          # Clear all cached files
```

---

## 🚢 Production Deployment

```bash
# 1. Set environment variables
APP_ENV=production
APP_DEBUG=false

# 2. Install production-only dependencies
composer install --no-dev --optimize-autoloader

# 3. Cache everything for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Run migrations
php artisan migrate --force
```

> For a proper production stack: use **Nginx** or **Apache** as a web server, **PHP-FPM** as the process manager, and swap SQLite for **MySQL** or **PostgreSQL** by updating `DB_CONNECTION` and related `.env` variables.

---

## 🔧 Troubleshooting

### `&&` is not valid in PowerShell
PowerShell 5.1 does not support `&&`. Run commands one at a time:
```powershell
cd "path\to\project"
php artisan serve
```

### `UNIQUE constraint failed`
A record with that code/SKU already exists. Use a different value or wipe the database:
```bash
php artisan migrate:fresh
```

### `Application key not set`
```bash
php artisan key:generate
```

### Blank page or 500 error
```bash
# Check the error log
cat storage/logs/laravel.log

# Clear all caches
php artisan optimize:clear
```

### Port 8000 already in use
```bash
php artisan serve --port=8080
```

---

## 🗺️ Roadmap

- [ ] Authentication & role-based access (Admin, Staff, Viewer)
- [ ] Outbound order management
- [ ] Barcode / QR code scanning support
- [ ] Inventory reports — export to CSV / PDF
- [ ] Real-time notifications via Laravel Broadcasting
- [ ] Docker / Docker Compose setup
- [ ] PHPUnit feature and unit test suite
- [ ] REST API authentication via Laravel Sanctum
- [ ] MySQL / PostgreSQL support for production scale

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).
