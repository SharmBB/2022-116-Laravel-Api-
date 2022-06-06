<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibrariesTableSeeder extends Seeder
{
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('librarys')->insert([
            'title' => 'library1',
            'description' => 'library1',
            'image' => 'image1'
          
            
        ]);
        //
    }
}
