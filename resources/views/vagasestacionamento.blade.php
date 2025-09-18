@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Vagas do Estacionamento</h1>
            <h5>Projeto: {{ $estacionamento->projeto->nomeProjeto ?? 'Sem Projeto' }}</h5>
            <p>Grids: {{ $estacionamento->gridsEixoX }} x {{ $estacionamento->gridsEixoY }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-dark">Voltar para Dashboard</a>
    </div>

    {{-- Linha principal --}}
    <div class="row">
        {{-- Grid --}}
        <div class="col-md-6 d-flex justify-content-center">
            <div id="gridContainer" class="grid shadow-sm p-2"></div>
        </div>

        {{-- Toolbox --}}
        <div class="col-md-2">
            <div class="card p-3 shadow-sm mb-3">
                <h5>Toolbox</h5>
                <div class="d-flex flex-column gap-2">
                    <button id="btnCarro" class="btn btn-dark"><i class="bi bi-car-front-fill"></i> Vaga Carro</button>
                    <button id="btnMoto" class="btn btn-dark"><i class="bi bi-bicycle"></i> Vaga Moto</button>
                    <button id="btnPreferencial" class="btn btn-dark"><i class="bi bi-person-fill"></i> Vaga Preferencial</button>
                    <button id="btnEspecial" class="btn btn-dark"><i class="bi bi-handicap"></i> Vaga Especial</button>
                    <button id="btnLimpar" class="btn btn-dark">Limpar Vagas</button>
                    <button id="btnSalvar" class="btn btn-success">Finalizar Estacionamento</button>
                </div>
            </div>
        </div>

        {{-- Resumo --}}
        <div class="col-md-4">
            <div class="card p-3 shadow-sm mb-3">
                <h5>Resumo de Vagas</h5>
                <table class="table table-bordered table-sm text-center mb-0" id="resumoTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Setor</th>
                            <th scope="col">Carro</th>
                            <th scope="col">Moto</th>
                            <th scope="col">Preferencial</th>
                            <th scope="col">Especial</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estacionamento->setores as $setor)
                        <tr data-setor="{{ $setor->nomeSetor }}">
                            <td scope="row">{{ $setor->nomeSetor }}</td>
                            <td class="carro">0</td>
                            <td class="moto">0</td>
                            <td class="preferencial">0</td>
                            <td class="especial">0</td>
                        </tr>
                        @endforeach
                        <tr id="totalVagasRow">
                            <td><strong>Total Vagas</strong></td>
                            <td class="carro fw-bold">0</td>
                            <td class="moto fw-bold">0</td>
                            <td class="preferencial fw-bold">0</td>
                            <td class="especial fw-bold">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card p-3 shadow-sm">
                <h5>Máximo de Vagas</h5>
                <table class="table table-bordered table-sm text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Tipo de Vaga</th>
                            <th>Máximo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Carro</td><td id="maxCarro">{{ $estacionamento->vagasCarro }}</td></tr>
                        <tr><td>Moto</td><td id="maxMoto">{{ $estacionamento->vagasMoto }}</td></tr>
                        <tr><td>Preferencial</td><td id="maxPref">{{ $estacionamento->vagasPreferencial }}</td></tr>
                        <tr><td>Especial (PCD)</td><td id="maxEspecial">{{ $estacionamento->vagasPcd }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Estilos --}}
<style>
.grid {
    display: grid;
    gap: 6px;
    margin-top: 10px;
    background: #e9ecef;
    border-radius: 4px;
    padding: 5px;
}
.grid-cell {
    width: 40px;
    height: 60px;
    border: 1px solid #000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    position: relative;
    transition: background 0.2s;
}
.grid-cell .vaga-icon { pointer-events: none; font-size: 18px; }

.over-limit { background: #f8d7da !important; }
.col-md-6 { display: flex; justify-content: center; }
</style>

{{-- Scripts --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const gridContainer = document.getElementById("gridContainer");
    const gridsX = {{ $estacionamento->gridsEixoX }};
    const gridsY = {{ $estacionamento->gridsEixoY }};
    gridContainer.style.gridTemplateColumns = `repeat(${gridsX}, 40px)`;

    const setores = @json($estacionamento->setores);

    const tiposVaga = {
        carro: '<i class="bi bi-car-front-fill vaga-icon" data-tipo="carro"></i>',
        moto: '<i class="bi bi-bicycle vaga-icon" data-tipo="moto"></i>',
        preferencial: '<i class="bi bi-person-fill vaga-icon" data-tipo="preferencial"></i>',
        especial: '<i class="bi bi-handicap vaga-icon" data-tipo="especial"></i>'
    };
    let tipoAtivo = null;

    document.getElementById('btnCarro').addEventListener('click', ()=> tipoAtivo='carro');
    document.getElementById('btnMoto').addEventListener('click', ()=> tipoAtivo='moto');
    document.getElementById('btnPreferencial').addEventListener('click', ()=> tipoAtivo='preferencial');
    document.getElementById('btnEspecial').addEventListener('click', ()=> tipoAtivo='especial');

    document.getElementById('btnLimpar').addEventListener('click', ()=> {
        document.querySelectorAll('.grid-cell').forEach(c => c.innerHTML='');
        atualizarResumo();
    });

    document.getElementById('btnSalvar').addEventListener('click', ()=> {
        alert('Salvar vagas - placeholder');
    });

    // Construir grid
    for(let y=1;y<=gridsY;y++){
        for(let x=1;x<=gridsX;x++){
            const cell = document.createElement('div');
            cell.className='grid-cell';
            cell.dataset.x=x;
            cell.dataset.y=y;

            setores.forEach(setor=>{
                setor.grids.forEach(g=>{
                    if(g.posX==x && g.posY==y){
                        cell.dataset.setor=setor.nomeSetor;
                        cell.style.backgroundColor=setor.corSetor || '#ccc';
                    }
                });
            });

            // Toggle de vaga
            cell.addEventListener('click', ()=>{
                if(!tipoAtivo) return;
                const existingIcon = cell.querySelector('.vaga-icon');
                const tipoAtual = existingIcon ? existingIcon.dataset.tipo : null;

                if(tipoAtual === tipoAtivo){
                    existingIcon.remove();
                } else {
                    cell.innerHTML = tiposVaga[tipoAtivo];
                }
                atualizarResumo();
            });

            gridContainer.appendChild(cell);
        }
    }

    function atualizarResumo(){
        const resumo = {};
        setores.forEach(s=> resumo[s.nomeSetor]={carro:0,moto:0,preferencial:0,especial:0});

        document.querySelectorAll('.grid-cell').forEach(cell=>{
            const setor = cell.dataset.setor;
            const icon = cell.querySelector('.vaga-icon');
            if(icon && setor){
                const tipo = icon.dataset.tipo;
                resumo[setor][tipo]++;
            }
        });

        let totalGeral = {carro:0,moto:0,preferencial:0,especial:0};

        Object.keys(resumo).forEach(setor=>{
            const row = document.querySelector(`tr[data-setor="${setor}"]`);
            if(row){
                row.querySelector('.carro').textContent = resumo[setor].carro;
                row.querySelector('.moto').textContent = resumo[setor].moto;
                row.querySelector('.preferencial').textContent = resumo[setor].preferencial;
                row.querySelector('.especial').textContent = resumo[setor].especial;

                // Destacar se ultrapassar máximo
                if(resumo[setor].carro > {{ $estacionamento->vagasCarro }} ||
                   resumo[setor].moto > {{ $estacionamento->vagasMoto }} ||
                   resumo[setor].preferencial > {{ $estacionamento->vagasPreferencial }} ||
                   resumo[setor].especial > {{ $estacionamento->vagasPcd }}) {
                       row.classList.add('over-limit');
                } else {
                       row.classList.remove('over-limit');
                }

                totalGeral.carro += resumo[setor].carro;
                totalGeral.moto += resumo[setor].moto;
                totalGeral.preferencial += resumo[setor].preferencial;
                totalGeral.especial += resumo[setor].especial;
            }
        });

        const totalRow = document.getElementById('totalVagasRow');
        totalRow.querySelector('.carro').textContent = totalGeral.carro;
        totalRow.querySelector('.moto').textContent = totalGeral.moto;
        totalRow.querySelector('.preferencial').textContent = totalGeral.preferencial;
        totalRow.querySelector('.especial').textContent = totalGeral.especial;

        // Destacar total geral se ultrapassar máximo
        if(totalGeral.carro >= {{ $estacionamento->vagasCarro }} ||
           totalGeral.moto >= {{ $estacionamento->vagasMoto }} ||
           totalGeral.preferencial >= {{ $estacionamento->vagasPreferencial }} ||
           totalGeral.especial >= {{ $estacionamento->vagasPcd }}) {
               totalRow.classList.add('over-limit');
        } else {
               totalRow.classList.remove('over-limit');
        }
    }
});
</script>
@endsection
