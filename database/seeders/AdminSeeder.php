<?php

namespace Database\Seeders;

use App\Models\Superviseur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Superviseur::factory()->create([
            'nom_sup' => 'SYG_ADMIN',
            'email' => 'admin@nmk.com',
            'password' => Hash::make('password'),
        ]);
        Superviseur::factory()->create([
            'nom_sup' => 'admin_test',
            'email' => 'admintest@nmk.com',
            'password' => Hash::make('password'),
        ]);

        Superviseur::factory(2)->create();

    }
}
