<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_setoresGrid', function (Blueprint $table) {
            $table->id('idGrid');
            
            // CORREÇÃO: Especifica a coluna PK da tabela tb_setores
            $table->foreignId('idSetor')
                  ->constrained('tb_setores', 'idSetor') // ← Adicionado segundo parâmetro
                  ->onDelete('cascade');
            
            $table->integer('posX');
            $table->integer('posY');
            $table->timestamps();
            
            $table->unique(['idSetor', 'posX', 'posY']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('tb_setoresGrid');
    }
};
