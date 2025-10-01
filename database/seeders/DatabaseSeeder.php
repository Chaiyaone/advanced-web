<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
public function run()
{
	// Ensure branch records exist before inserting products
	$this->call(CategoryTableSeeder::class);
	$this->call(BranchTableSeeder::class);
	$this->call(ProductTableSeeder::class);

}
}
