<?php

use Illuminate\Database\Seeder;
use App\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            'Bali',
            'Yogyakarta',
            'Surakarta',
            'Jakarta',
            'Palembang',
            'Medan',
            'Makassar',
            'Lombok',
            'Bogor',
            'Bandung',
            'Padang',
            'Jambi',
            'Surabaya',
            'Banyuwangi',
            'Pacitan',
            'Semarang'
        ];

        for($i = 0;$i<count($locations);$i++){
            Location::create([
                'name' => $locations[$i]
            ]);
        }
    }
}
