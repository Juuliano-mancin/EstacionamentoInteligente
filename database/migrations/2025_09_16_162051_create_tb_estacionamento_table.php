<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_estacionamento', function (Blueprint $table) {
            $table->id('idEstacionamento'); // PK + Auto Increment
            $table->unsignedInteger('gridsEixoX');
            $table->unsignedInteger('gridsEixoY');
            $table->unsignedInteger('vagasCarro');
            $table->unsignedInteger('vagasMoto');
            $table->unsignedInteger('vagasPreferencial');
            $table->unsignedInteger('vagasPcd');

            // Foreign key para tb_projeto
            $table->unsignedBigInteger('idProjeto'); // mesmo nome da PK da tabela pai
            $table->foreign('idProjeto')
                  ->references('idProjeto') // campo correto em tb_projeto
                  ->on('tb_projeto')
                  ->onDelete('cascade'); // Exclui estacionamentos ao excluir projeto

            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_estacionamento');
    }
};

