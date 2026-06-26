<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Location;
use App\Models\MovementOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@warehouse.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        // ── Zones ──────────────────────────────────────────────────────────
        $zones = [
            ['name' => 'Inbound Dock',     'code' => 'IB-DOCK',  'type' => 'inbound',      'description' => 'Receiving area for all incoming goods'],
            ['name' => 'Outbound Dock',    'code' => 'OB-DOCK',  'type' => 'outbound',     'description' => 'Dispatch area for all outgoing shipments'],
            ['name' => 'General Storage A','code' => 'GEN-A',    'type' => 'standard',     'description' => 'Main bulk storage — Row A'],
            ['name' => 'General Storage B','code' => 'GEN-B',    'type' => 'standard',     'description' => 'Main bulk storage — Row B'],
            ['name' => 'Cold Room 1',      'code' => 'COLD-1',   'type' => 'cold_storage', 'description' => 'Temperature controlled: 2°C – 8°C'],
            ['name' => 'Cold Room 2',      'code' => 'COLD-2',   'type' => 'cold_storage', 'description' => 'Temperature controlled: -18°C (frozen)'],
            ['name' => 'Hazmat Vault',     'code' => 'HAZ-VAULT','type' => 'hazmat',       'description' => 'Restricted zone for flammable & corrosive materials'],
        ];

        $createdZones = [];
        foreach ($zones as $z) {
            $createdZones[$z['code']] = Zone::firstOrCreate(['code' => $z['code']], $z);
        }

        // ── Locations ──────────────────────────────────────────────────────
        // Format: [zone_code, code, x, y, z, max_weight, max_volume]
        $locations = [
            // General Storage A — 3×3 grid
            ['GEN-A', 'A-01-01', 1, 1, 1, 1000, 20],
            ['GEN-A', 'A-01-02', 1, 1, 2, 1000, 20],
            ['GEN-A', 'A-01-03', 1, 1, 3, 1000, 20],
            ['GEN-A', 'A-02-01', 1, 2, 1, 800,  15],
            ['GEN-A', 'A-02-02', 1, 2, 2, 800,  15],
            ['GEN-A', 'A-02-03', 1, 2, 3, 800,  15],
            ['GEN-A', 'A-03-01', 1, 3, 1, 600,  10],
            ['GEN-A', 'A-03-02', 1, 3, 2, 600,  10],
            ['GEN-A', 'A-03-03', 1, 3, 3, 600,  10],

            // General Storage B
            ['GEN-B', 'B-01-01', 5, 1, 1, 1200, 25],
            ['GEN-B', 'B-01-02', 5, 1, 2, 1200, 25],
            ['GEN-B', 'B-02-01', 5, 2, 1, 900,  18],
            ['GEN-B', 'B-02-02', 5, 2, 2, 900,  18],
            ['GEN-B', 'B-03-01', 5, 3, 1, 700,  12],

            // Cold Room 1
            ['COLD-1', 'C1-01-01', 10, 1, 1, 500, 8],
            ['COLD-1', 'C1-01-02', 10, 1, 2, 500, 8],
            ['COLD-1', 'C1-02-01', 10, 2, 1, 400, 6],

            // Cold Room 2
            ['COLD-2', 'C2-01-01', 12, 1, 1, 500, 8],
            ['COLD-2', 'C2-02-01', 12, 2, 1, 400, 6],

            // Hazmat Vault
            ['HAZ-VAULT', 'HV-01-01', 20, 1, 1, 300, 5],
            ['HAZ-VAULT', 'HV-01-02', 20, 1, 2, 300, 5],
        ];

        $createdLocations = [];
        foreach ($locations as [$zoneCode, $code, $x, $y, $z, $mw, $mv]) {
            $createdLocations[$code] = Location::firstOrCreate(['code' => $code], [
                'zone_id'    => $createdZones[$zoneCode]->id,
                'x_coord'    => $x,
                'y_coord'    => $y,
                'z_coord'    => $z,
                'max_weight' => $mw,
                'max_volume' => $mv,
                'is_active'  => true,
            ]);
        }

        // ── Products ───────────────────────────────────────────────────────
        $products = [
            ['sku' => 'SKU-BOLT-M8',   'name' => 'Bolt M8 x 30mm (Box/100)',    'weight' => 3.2,  'volume' => 0.5,  'requires_cold_storage' => false, 'is_hazmat' => false],
            ['sku' => 'SKU-BOLT-M12',  'name' => 'Bolt M12 x 50mm (Box/50)',    'weight' => 4.5,  'volume' => 0.6,  'requires_cold_storage' => false, 'is_hazmat' => false],
            ['sku' => 'SKU-PIPE-25',   'name' => 'PVC Pipe 25mm (6m length)',   'weight' => 8.0,  'volume' => 2.8,  'requires_cold_storage' => false, 'is_hazmat' => false],
            ['sku' => 'SKU-CABLE-NYY', 'name' => 'NYY Cable 4x10mm² (50m)',    'weight' => 22.0, 'volume' => 3.5,  'requires_cold_storage' => false, 'is_hazmat' => false],
            ['sku' => 'SKU-GLOVE-L',   'name' => 'Safety Gloves (Pair, L)',     'weight' => 0.3,  'volume' => 0.1,  'requires_cold_storage' => false, 'is_hazmat' => false],
            ['sku' => 'SKU-HELM-RED',  'name' => 'Safety Helmet Red',           'weight' => 0.5,  'volume' => 0.4,  'requires_cold_storage' => false, 'is_hazmat' => false],
            ['sku' => 'SKU-VACCINE-A', 'name' => 'Vaccine Type-A (10-dose)',    'weight' => 0.2,  'volume' => 0.05, 'requires_cold_storage' => true,  'is_hazmat' => false],
            ['sku' => 'SKU-INSULIN',   'name' => 'Insulin Vial (100 IU/mL)',   'weight' => 0.1,  'volume' => 0.02, 'requires_cold_storage' => true,  'is_hazmat' => false],
            ['sku' => 'SKU-FROZEN-MED','name' => 'Frozen Plasma (Unit)',        'weight' => 0.3,  'volume' => 0.03, 'requires_cold_storage' => true,  'is_hazmat' => false],
            ['sku' => 'SKU-SOL-IPA',   'name' => 'Isopropyl Alcohol 99% (5L)', 'weight' => 4.1,  'volume' => 0.5,  'requires_cold_storage' => false, 'is_hazmat' => true],
            ['sku' => 'SKU-ACID-HCL',  'name' => 'Hydrochloric Acid 37% (1L)', 'weight' => 1.3,  'volume' => 0.1,  'requires_cold_storage' => false, 'is_hazmat' => true],
        ];

        $createdProducts = [];
        foreach ($products as $p) {
            $createdProducts[$p['sku']] = Product::firstOrCreate(['sku' => $p['sku']], $p);
        }

        // ── Inventory ──────────────────────────────────────────────────────
        // [product_sku, location_code, quantity, reserved]
        $stocks = [
            ['SKU-BOLT-M8',   'A-01-01', 350, 50],
            ['SKU-BOLT-M8',   'A-01-02', 200, 0],
            ['SKU-BOLT-M12',  'A-01-03', 180, 20],
            ['SKU-PIPE-25',   'B-01-01', 60,  10],
            ['SKU-PIPE-25',   'B-01-02', 40,  0],
            ['SKU-CABLE-NYY', 'B-02-01', 25,  5],
            ['SKU-GLOVE-L',   'A-02-01', 500, 100],
            ['SKU-HELM-RED',  'A-02-02', 75,  0],
            ['SKU-VACCINE-A', 'C1-01-01', 300, 50],
            ['SKU-INSULIN',   'C1-01-02', 800, 200],
            ['SKU-FROZEN-MED','C2-01-01', 120, 30],
            ['SKU-SOL-IPA',   'HV-01-01', 90,  10],
            ['SKU-ACID-HCL',  'HV-01-02', 8,   0],
        ];

        foreach ($stocks as [$sku, $locCode, $qty, $reserved]) {
            Inventory::firstOrCreate(
                [
                    'product_id'  => $createdProducts[$sku]->id,
                    'location_id' => $createdLocations[$locCode]->id,
                ],
                ['quantity' => $qty, 'reserved_quantity' => $reserved]
            );
        }

        // ── Movement Orders ────────────────────────────────────────────────
        $orders = [
            // Completed inbounds
            ['SKU-BOLT-M8',   null,       'A-01-01', 350, 'inbound',  'completed'],
            ['SKU-BOLT-M8',   null,       'A-01-02', 200, 'inbound',  'completed'],
            ['SKU-BOLT-M12',  null,       'A-01-03', 180, 'inbound',  'completed'],
            ['SKU-VACCINE-A', null,       'C1-01-01',300, 'inbound',  'completed'],
            ['SKU-INSULIN',   null,       'C1-01-02',800, 'inbound',  'completed'],
            ['SKU-SOL-IPA',   null,       'HV-01-01', 90, 'inbound',  'completed'],
            ['SKU-ACID-HCL',  null,       'HV-01-02',  8, 'inbound',  'completed'],
            // Pending transfers
            ['SKU-BOLT-M8',   'A-01-01', 'A-02-01',  50, 'transfer', 'pending'],
            ['SKU-GLOVE-L',   'A-02-01', 'B-03-01',  30, 'transfer', 'pending'],
            // Cancelled
            ['SKU-PIPE-25',   'B-01-01', 'B-03-01',  10, 'transfer', 'cancelled'],
        ];

        foreach ($orders as [$sku, $srcCode, $dstCode, $qty, $type, $status]) {
            MovementOrder::create([
                'reference_number'        => 'MO-' . strtoupper(Str::random(10)),
                'product_id'              => $createdProducts[$sku]->id,
                'source_location_id'      => $srcCode ? $createdLocations[$srcCode]->id : null,
                'destination_location_id' => $createdLocations[$dstCode]->id,
                'quantity'                => $qty,
                'type'                    => $type,
                'status'                  => $status,
                'created_by'              => $user->id,
                'completed_at'            => $status === 'completed' ? now()->subDays(rand(1, 30)) : null,
                'created_at'              => now()->subDays(rand(1, 45)),
                'updated_at'              => now()->subDays(rand(0, 5)),
            ]);
        }
    }
}
