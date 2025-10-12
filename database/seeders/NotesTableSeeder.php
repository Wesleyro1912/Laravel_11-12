<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // create multiple users
        DB::table('notes')->insert([
            [
                'user_id' => 1,
                'title' => 'Nota 01',
                'text' => 'Ola mundo 01',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 2,
                'title' => 'Nota 02',
                'text' => 'Ola mundo 02',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 3,
                'title' => 'Nota 02',
                'text' => 'Ola mundo 03',
                'created_at' => date('Y-m-d H:i:s')
            ]
            ]);
    }
}
