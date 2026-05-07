<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::pluck('id', 'code');

        $suppliers = [
            ['Phsar Thmey Wholesale',   '023987001', 'sales@phsarthmey.example.com',  'Phsar Thmey, Phnom Penh',     'Mr. Bunna',  'BR-MAIN'],
            ['ABA Linen Co.',           '023987002', 'info@abalinen.example.com',     'St. 271, Phnom Penh',         'Ms. Sokha',  'BR-MAIN'],
            ['CamHotel Supplies',       '023987003', 'orders@camhotel.example.com',   'Battambang',                  'Mr. Vibol',  'BR-BB'],
            ['Angkor Cleaning Supplies','023987004', 'sales@angkorclean.example.com', 'Siem Reap',                   'Ms. Maly',   'BR-SR'],
        ];
        $createdSuppliers = [];
        foreach ($suppliers as [$name, $phone, $email, $addr, $contact, $branchCode]) {
            $createdSuppliers[$name] = Supplier::firstOrCreate(
                ['name' => $name],
                [
                    'branch_id'      => $branches[$branchCode] ?? null,
                    'phone'          => $phone,
                    'email'          => $email,
                    'address'        => $addr,
                    'contact_person' => $contact,
                    'status'         => 'active',
                ]
            );
        }

        $categories = ['Linen & Towels', 'Toiletries', 'Cleaning Supplies', 'F&B Stock', 'Office Supplies'];
        $createdCats = [];
        foreach ($categories as $cat) {
            $createdCats[$cat] = StockCategory::firstOrCreate(
                ['name' => $cat],
                ['description' => $cat . ' standard items']
            );
        }

        $items = [
            ['Bath Towel — White',   'BTW-001', 'pcs',   2.50,  4.00, 120, 30, 'Linen & Towels',     'ABA Linen Co.'],
            ['Bed Sheet — Queen',    'BSQ-001', 'pcs',   8.00, 15.00,  80, 20, 'Linen & Towels',     'ABA Linen Co.'],
            ['Pillow Case',          'PCS-001', 'pcs',   1.20,  2.50, 200, 50, 'Linen & Towels',     'ABA Linen Co.'],
            ['Shampoo Bottle 30ml',  'SHM-030', 'pcs',   0.30,  0.80, 600,150, 'Toiletries',         'Phsar Thmey Wholesale'],
            ['Body Soap 25g',        'SOP-025', 'pcs',   0.20,  0.50, 800,200, 'Toiletries',         'Phsar Thmey Wholesale'],
            ['Toilet Paper Roll',    'TPR-001', 'roll',  0.50,  1.00, 400,100, 'Toiletries',         'Phsar Thmey Wholesale'],
            ['Glass Cleaner 1L',     'GCL-1L',  'bottle',2.50,  null,  30, 10, 'Cleaning Supplies',  'Angkor Cleaning Supplies'],
            ['Floor Detergent 5L',   'FLD-5L',  'gal',   8.00,  null,  20,  5, 'Cleaning Supplies',  'Angkor Cleaning Supplies'],
            ['Mineral Water 500ml',  'MNW-500', 'bottle',0.20,  1.00, 500,100, 'F&B Stock',          'CamHotel Supplies'],
            ['Beer (Local) 330ml',   'BLR-330', 'can',   0.80,  2.00, 240, 60, 'F&B Stock',          'CamHotel Supplies'],
            ['Coffee Sachets',       'COF-001', 'pcs',   0.10,  null,1000,250, 'F&B Stock',          'Phsar Thmey Wholesale'],
            ['A4 Paper Ream',        'A4P-001', 'ream',  3.50,  null,  40, 10, 'Office Supplies',    'Phsar Thmey Wholesale'],
        ];

        foreach ($items as [$name, $sku, $unit, $purchase, $sell, $stock, $min, $cat, $supplierName]) {
            $item = StockItem::firstOrCreate(
                ['sku' => $sku],
                [
                    'branch_id'         => $branches['BR-MAIN'] ?? null,
                    'stock_category_id' => $createdCats[$cat]->id,
                    'supplier_id'       => $createdSuppliers[$supplierName]->id,
                    'name'              => $name,
                    'unit'              => $unit,
                    'purchase_price'    => $purchase,
                    'selling_price'     => $sell,
                    'current_stock'     => $stock,
                    'minimum_stock'     => $min,
                    'expiry_date'       => $cat === 'F&B Stock' ? now()->addMonths(6)->toDateString() : null,
                    'status'            => 'active',
                ]
            );

            /* Initial purchase IN movement so audit trail and stock-cards show data */
            StockMovement::firstOrCreate(
                [
                    'stock_item_id' => $item->id,
                    'movement_type' => 'in',
                    'reference_type'=> 'seed',
                    'reference_id'  => 1,
                ],
                [
                    'branch_id'   => $item->branch_id,
                    'quantity'    => $stock,
                    'unit_cost'   => $purchase,
                    'total_cost'  => $stock * $purchase,
                    'note'        => 'Initial stock — seeded',
                    'movement_at' => now()->subDays(45),
                    'created_by'  => 1,
                ]
            );

            /* One OUT movement to demonstrate consumption */
            StockMovement::firstOrCreate(
                [
                    'stock_item_id' => $item->id,
                    'movement_type' => 'out',
                    'reference_type'=> 'seed',
                    'reference_id'  => 2,
                ],
                [
                    'branch_id'   => $item->branch_id,
                    'quantity'    => max(1, intdiv((int) $stock, 10)),
                    'unit_cost'   => $purchase,
                    'total_cost'  => max(1, intdiv((int) $stock, 10)) * $purchase,
                    'note'        => 'Consumed by guest rooms — seeded',
                    'movement_at' => now()->subDays(7),
                    'created_by'  => 1,
                ]
            );
        }
    }
}
