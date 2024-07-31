<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\Configuration\CoreEnumTableSeeder;
use Database\Seeders\Configuration\CoreEnumClientSeeder;
use Database\Seeders\Configuration\CoreEnumCategorySeeder;
use Database\Seeders\Configuration\CoreEnumSeamstresSeeder;

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
        $this->call(CoreEnumClientSeeder::class);
        $this->call(CoreEnumSeamstresSeeder::class);
    }
}
