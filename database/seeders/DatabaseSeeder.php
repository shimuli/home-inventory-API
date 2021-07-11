<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Products;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // disable events
        User::flushEventListeners();
        Products::flushEventListeners();
        Category::flushEventListeners();

        // if ($this->command->confirm('Hello Developer, Do you want refresh the database?', true)) {
        //     $this->command->call('migrate:refresh');
        //     $this->command->info('Awesome! Database was refreshed');
        // }
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductsSeeder::class,
        ]);

    }
}
