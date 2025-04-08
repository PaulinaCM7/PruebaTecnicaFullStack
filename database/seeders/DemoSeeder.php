<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear Admin
        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Crear Cliente
        User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@demo.com',
            'password' => Hash::make('password'),
            'role' => 'cliente',
        ]);

        // Crear Medios
        Media::create([
            'name' => 'Pantalla LED Reforma',
            'location' => 'CDMX - Reforma',
            'type' => 'pantalla',
            'image' => 'led-reforma.jpg',
            'price_per_day' => 1500.00,
        ]);

        Media::create([
            'name' => 'Valla Publicitaria Sur',
            'location' => 'CDMX - Periférico Sur',
            'type' => 'valla',
            'image' => 'valla-sur.jpg',
            'price_per_day' => 900.00,
        ]);
    }
}

