<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Daniel Canivezo',
                'email' => 'Daniel@fatec',
                'password' => Hash::make('fatec123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juliano Mancini',
                'email' => 'Juliano@fatec',
                'password' => Hash::make('fatec123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laura Brianti',
                'email' => 'Laura@fatec',
                'password' => Hash::make('fatec123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monise Jacheta',
                'email' => 'Monise@fatec',
                'password' => Hash::make('fatec123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'Daniel@fatec',
            'Juliano@fatec',
            'Laura@fatec',
            'Monise@fatec'
        ])->delete();
    }
};
