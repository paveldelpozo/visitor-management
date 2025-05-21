<?php

namespace Database\Seeders;

use App\Models\HeadphoneStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeadphoneStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeadphoneStock::firstOrCreate([], ['total' => 300]);
    }
}
