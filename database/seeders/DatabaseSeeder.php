<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(HeadphoneStockSeeder::class);
        $this->call(RolesSeeder::class);

        $user = User::firstOrCreate([
            'name' => 'Pavel del Pozo',
            'email' => 'paveldelpozo@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');
    }
}
