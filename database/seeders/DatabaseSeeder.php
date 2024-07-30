<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\Product\ProductSeeder;
use Database\Seeders\Configuration\CoreEnumTableSeeder;
use Database\Seeders\Configuration\CoreEnumCategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CoreEnumTableSeeder::class);
        $this->call(CoreEnumCategorySeeder::class);
    }
}
