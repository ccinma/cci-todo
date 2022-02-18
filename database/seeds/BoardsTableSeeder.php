<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("boards")->insert([
            "name" => "Projet ASP.NET",
            "user_id" => 1,
            "workspace_id" => 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
        DB::table("boards")->insert([
            "name" => "Projet Laravel",
            "user_id" => 1,
            "workspace_id" => 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
