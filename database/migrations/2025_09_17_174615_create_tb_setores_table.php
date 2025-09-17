<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_setores', function (Blueprint $table) {
            $table->id('idSetor');
            
            // CORREÇÃO: Especifica a coluna PK da tabela referenciada
            $table->foreignId('idEstacionamento')
                  ->constrained('tb_estacionamento', 'idEstacionamento')
                  ->onDelete('cascade');
            
            $table->string('nomeSetor');
            $table->string('corSetor', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tb_setores');
    }
};
