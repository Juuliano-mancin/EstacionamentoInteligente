<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_projeto', function (Blueprint $table) {
            $table->id('idProjeto'); // PK + Auto Increment (renomeado)
            $table->string('nomeProjeto', 100)->unique(); // renomeado
            $table->string('nomeFantasia', 150);
            $table->string('razaoSocial', 150);
            $table->string('contatoNome', 100);
            $table->string('contatoCelular', 20)->nullable();
            $table->string('contatoEmail', 150)->nullable();
            $table->string('enderecoLogradouro', 150);
            $table->string('enderecoNumero', 10);
            $table->string('enderecoComplemento', 50)->nullable();
            $table->string('enderecoBairro', 100);
            $table->string('enderecoCidade', 100);
            $table->char('enderecoEstado', 2);
            $table->string('enderecoCEP', 9);
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_projeto');
    }
};