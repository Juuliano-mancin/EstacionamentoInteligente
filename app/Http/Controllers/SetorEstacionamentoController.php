<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Estacionamento;
use App\Models\Setor;
use App\Models\SetorGrid;

class SetorEstacionamentoController extends Controller
{
    public function index()
    {
        // lista todos os estacionamentos para popular o dropdown
        $estacionamentos = Estacionamento::all();

        return view('setoresestacionamento', compact('estacionamentos'));
    }

    public function show($id)
    {
        // retorna um estacionamento específico em JSON (usado via AJAX)
        $estacionamento = Estacionamento::findOrFail($id);

        return response()->json($estacionamento);
    }
}
