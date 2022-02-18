<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkspacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("workspaces")->insert([
            "user_id" => 1,
            "name" => "Yohan Mawyn - CCI",
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
