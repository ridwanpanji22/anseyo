<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            [
                'number' => 'A1',
                'capacity' => 4,
                'description' => 'Meja untuk 4 orang',
            ],
            [
                'number' => 'A2',
                'capacity' => 4,
                'description' => 'Meja untuk 4 orang',
            ],
            [
                'number' => 'A3',
                'capacity' => 4,
                'description' => 'Meja untuk 4 orang',
            ],
            [
                'number' => 'B1',
                'capacity' => 6,
                'description' => 'Meja untuk 6 orang',
            ],
            [
                'number' => 'B2',
                'capacity' => 6,
                'description' => 'Meja untuk 6 orang',
            ],
            [
                'number' => 'C1',
                'capacity' => 8,
                'description' => 'Meja untuk 8 orang',
            ],
            [
                'number' => 'C2',
                'capacity' => 8,
                'description' => 'Meja untuk 8 orang',
            ],
        ];

        foreach ($tables as $table) {
            Table::create([
                'number' => $table['number'],
                'qr_code' => 'table-' . $table['number'],
                'capacity' => $table['capacity'],
                'description' => $table['description'],
                'status' => 'available',
                'is_active' => true,
            ]);
        }
    }
}
