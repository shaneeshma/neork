<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class HobbySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hobbies')->insert([
            ['id'=>1,  'hobby' => 'Programming'],
            ['id'=>2,  'hobby' => 'Reading'],
            ['id'=>3,  'hobby' => 'Games'],
            ['id'=>4,  'hobby' => 'Photography'],
        ]);
    }
}