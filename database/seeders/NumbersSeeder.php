<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Number;

class NumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar números de 1 a 200
        for ($i = 1; $i <= 200; $i++) {
            Number::create([
                'number' => $i,
                'status' => 'disponivel'
            ]);
        }
    }
}
