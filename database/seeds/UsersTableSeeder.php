<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            "email" => "mawyn.nhek@gmail.com",
            "name" => "Mawyn",
            "password" => "test",
            "created_at" => now(),
            "updated_at" => now(),
        ]);
        DB::table("users")->insert([
            "email" => "berthier.yohan@gmail.com",
            "name" => "Yohan",
            "password" => "test",
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
