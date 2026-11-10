<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role'     => 'admin',
        ]);

        Room::create([
            'foto'            => 'images/standard.jpg',
            'nomor_kamar'     => '101',
            'tipe_kamar'      => 'standard',
            'harga_per_malam' => 300000,
            'status'          => 'tersedia',
            'deskripsi'       => 'Kamar Standard dengan fasilitas kasur Single dan AC.',
        ]);

        Room::create([
            'foto'            => 'images/superior.jpg',
            'nomor_kamar'     => '201',
            'tipe_kamar'      => 'superior',
            'harga_per_malam' => 500000,
            'status'          => 'tersedia',
            'deskripsi'       => 'Kamar Superior dengan kasur Queen, TV, dan AC.',
        ]);

        Room::create([
            'foto'            => 'images/deluxe.jpg',
            'nomor_kamar'     => '301',
            'tipe_kamar'      => 'deluxe',
            'harga_per_malam' => 750000,
            'status'          => 'tersedia',
            'deskripsi'       => 'Kamar Deluxe luas dengan King size bed, TV, Balkon, dan Sarapan.',
        ]);
    }
}
