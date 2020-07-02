<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Sejarah dan Budaya',
            'Memasak',
            'Seni dan Musik',
            'Sulap',
            'Keahlian',
            'Olahraga',
            'Wisata Malam',
            'Aktivitas Luar Ruangan',
            'Seni Tari',
            'Kuliner',
            'Pantai',
            'Gunung'
        ];

        for($i = 0;$i<count($categories);$i++){
            Category::create([
                'name' => $categories[$i]
            ]);
        }
    }
}
