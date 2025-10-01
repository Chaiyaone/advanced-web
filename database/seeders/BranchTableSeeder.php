<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert branches with explicit IDs so product seeder can reference them
        DB::table('branch')->insert([
            ['id' => 1, 'name' => 'นครนายก', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'กรุงเทพมหานคร', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'ชลบุรี', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
