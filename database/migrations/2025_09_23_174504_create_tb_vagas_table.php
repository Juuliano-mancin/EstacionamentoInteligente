<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_vagas', function (Blueprint $table) {
            $table->id('idVaga'); // PK
            
            // Chaves estrangeiras
            $table->unsignedBigInteger('idEstacionamento');
            $table->unsignedBigInteger('idSetor')->nullable();

            // Posição no grid
            $table->integer('posVagaX');
            $table->integer('posVagaY');

            // Tipo da vaga
            $table->enum('tipo', ['carro', 'moto', 'preferencial', 'pcd'])->nullable();

            // Status da vaga (true = ocupada, false = livre)
            $table->boolean('status')->default(false);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('idEstacionamento')
                  ->references('idEstacionamento')
                  ->on('tb_estacionamento')
                  ->onDelete('cascade');

            $table->foreign('idSetor')
                  ->references('idSetor')
                  ->on('tb_setores')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_vagas');
    }
};