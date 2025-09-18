@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-dark">Voltar para Dashboard</a>
    </div>

    <h1 class="mb-4">Setores do Estacionamento</h1>

    {{-- Dropdown do estacionamento --}}
    <div class="mb-3">
        <label for="estacionamentoSelect" class="form-label">Selecione um estacionamento:</label>
        <select id="estacionamentoSelect" class="form-select">
            <option value="">-- Escolha --</option>
            @foreach($estacionamentos as $estacionamento)
                <option value="{{ $estacionamento->idEstacionamento }}" data-id-projeto="{{ $estacionamento->idProjeto }}">
                    {{ $estacionamento->projeto->nomeProjeto ?? 'Sem Projeto' }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Conteúdo principal --}}
    <div id="mainContent" style="display:none;">
        {{-- Informações --}}
        <div id="estacionamentoInfo" class="mb-4">
            <h3>Informações:</h3>
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Grids</th>
                        <th>Vagas Carro</th>
                        <th>Vagas Moto</th>
                        <th>Vagas Preferencial</th>
                        <th>Vagas PCD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="infoGrid"></td>
                        <td id="infoCarro"></td>
                        <td id="infoMoto"></td>
                        <td id="infoPreferencial"></td>
                        <td id="infoPcd"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Grid + Toolbox --}}
        <div class="d-flex gap-2">
            {{-- Grid --}}
            <div id="gridWrapper" style="flex:1;">
                <div id="gridContainer" class="grid"></div>
            </div>

            {{-- Toolbox + Cards --}}
            <div id="toolbox" style="width:300px;">
                <div class="card p-3 shadow-sm">
                    <h5 class="mb-3">Configurações</h5>
                    <label for="numSetores" class="form-label">Número de setores (máx. 10):</label>
                    <input type="number" id="numSetores" class="form-control mb-2" min="1" max="10">
                    <button id="btnSetores" class="btn btn-primary w-100 mb-2">OK</button>
                    <button id="btnLimpar" class="btn btn-danger w-100 mb-2">Limpar setores</button>
                    <button id="btnSalvar" class="btn btn-success w-100 mb-2">Salvar Setores</button>

                    {{-- Cards de setores --}}
                    <div id="setoresContainer" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSRF para fetch --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    #gridWrapper { display: flex; justify-content: center; }
    .grid { display: grid; gap: 6px; margin-top: 20px; }
    .grid-cell {
        width: 40px; height: 60px; background: #f0f0f0;
        border: 1px solid #000; display: flex;
        align-items: center; justify-content: center;
        font-size: 12px; opacity: 1; position: relative;
        user-select: none; cursor: pointer;
    }
    .grid-cell span { opacity: 0.2; position: relative; z-index: 1; }
    .grid-cell.temp-highlight { outline: 2px dashed rgba(0,0,0,0.6); }

    .setor-card {
        width: 70px; height: 70px; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; border-radius: 8px;
        cursor: pointer; border: 2px solid transparent; transition: border 0.2s;
    }
    .setor-card.active { border: 2px solid #000; }

    #mainContent .d-flex { gap: 10px; }
</style>

<script>
const coresSetores = ['#007bff','#28a745','#dc3545','#ffc107','#17a2b8','#6f42c1','#fd7e14','#20c997','#343a40','#6610f2'];
let setorAtivo = null;
let gridState = {};
let isMouseDown = false;
let startCell = null;
let dragCellsTemp = [];

// Seleção do estacionamento
document.getElementById('estacionamentoSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const idProjeto = selectedOption.dataset.idProjeto;
    const idEstacionamento = this.value;

    if(!idEstacionamento || !idProjeto){ 
        document.getElementById('mainContent').style.display='none'; 
        return; 
    }

    fetch(`/setores-estacionamento/${idProjeto}/${idEstacionamento}`, { headers: {"Accept": "application/json"} })
    .then(res=>res.json())
    .then(data=>{
        if(data.error){ alert(data.error); return; }

        document.getElementById('mainContent').style.display='block';
        document.getElementById('infoGrid').textContent = `${data.gridsEixoX} x ${data.gridsEixoY}`;
        document.getElementById('infoCarro').textContent = data.vagasCarro;
        document.getElementById('infoMoto').textContent = data.vagasMoto;
        document.getElementById('infoPreferencial').textContent = data.vagasPreferencial;
        document.getElementById('infoPcd').textContent = data.vagasPcd;

        const gridContainer = document.getElementById('gridContainer');
        gridContainer.style.gridTemplateColumns = `repeat(${data.gridsEixoX}, 50px)`;
        gridContainer.innerHTML = '';
        gridState = {};

        for(let y=1;y<=data.gridsEixoY;y++){
            for(let x=1;x<=data.gridsEixoX;x++){
                const cell=document.createElement('div');
                cell.className='grid-cell';
                cell.dataset.x=x;
                cell.dataset.y=y;

                const span=document.createElement('span');
                span.textContent=`${x},${y}`;
                cell.appendChild(span);

                cell.addEventListener('mousedown', e=>{
                    if(!setorAtivo) return;
                    isMouseDown=true;
                    startCell=cell;
                    dragCellsTemp=[cell];
                    highlightBlock(cell,cell);
                    e.preventDefault();
                });

                cell.addEventListener('mouseenter', e=>{
                    if(isMouseDown && setorAtivo){
                        dragCellsTemp=[];
                        highlightBlock(startCell, cell);
                    }
                });

                cell.addEventListener('contextmenu', e=>{
                    e.preventDefault();
                    const key = `${cell.dataset.x}_${cell.dataset.y}`;
                    if(gridState[key]){
                        delete gridState[key];
                        cell.style.backgroundColor='#f0f0f0';
                        cell.classList.remove('temp-highlight');
                    }
                });

                gridContainer.appendChild(cell);
            }
        }

        document.body.addEventListener('mouseup', e=>{
            if(isMouseDown && dragCellsTemp.length>0){
                const cor = coresSetores[setorAtivo.charCodeAt(0)-65];
                dragCellsTemp.forEach(cell=>{
                    const key=`${cell.dataset.x}_${cell.dataset.y}`;
                    gridState[key]=setorAtivo;
                    cell.style.backgroundColor=cor;
                    cell.classList.remove('temp-highlight');
                });
                dragCellsTemp=[];
            }
            isMouseDown=false;
            startCell=null;
        });
    })
    .catch(err=>{
        console.error(err);
        alert("Erro ao carregar estacionamento: "+err.message);
    });
});

// Highlight bloco
function highlightBlock(cell1, cell2){
    const x1=Math.min(cell1.dataset.x,cell2.dataset.x);
    const x2=Math.max(cell1.dataset.x,cell2.dataset.x);
    const y1=Math.min(cell1.dataset.y,cell2.dataset.y);
    const y2=Math.max(cell1.dataset.y,cell2.dataset.y);

    document.querySelectorAll('.grid-cell').forEach(c=>c.classList.remove('temp-highlight'));
    dragCellsTemp=[];

    document.querySelectorAll('.grid-cell').forEach(c=>{
        const cx=parseInt(c.dataset.x);
        const cy=parseInt(c.dataset.y);
        if(cx>=x1 && cx<=x2 && cy>=y1 && cy<=y2){
            c.classList.add('temp-highlight');
            dragCellsTemp.push(c);
        }
    });
}

// Gerar setores
document.getElementById('btnSetores').addEventListener('click', function(){
    const num=parseInt(document.getElementById('numSetores').value);
    const container=document.getElementById('setoresContainer');
    container.innerHTML='';
    setorAtivo=null;

    if(!num||num<1||num>10){ 
        alert('Digite um número válido de setores (1 a 10).'); 
        return; 
    }

    for(let i=0;i<num;i++){
        const card=document.createElement('div');
        card.className='setor-card';
        card.style.backgroundColor=coresSetores[i];
        card.textContent=`Setor ${String.fromCharCode(65+i)}`;
        card.dataset.letra = String.fromCharCode(65+i);

        card.addEventListener('click', function(){
            document.querySelectorAll('.setor-card').forEach(c=>c.classList.remove('active'));
            this.classList.add('active');
            setorAtivo=this.dataset.letra;
        });

        container.appendChild(card);
    }
});

// Limpar setores
document.getElementById('btnLimpar').addEventListener('click', function(){
    document.querySelectorAll('.grid-cell').forEach(cell=>{
        cell.style.backgroundColor='#f0f0f0';
        cell.classList.remove('temp-highlight');
    });
    gridState={};
    dragCellsTemp=[];
});

// Salvar setores
document.getElementById('btnSalvar').addEventListener('click', function(){
    const setoresContainer = document.getElementById('setoresContainer');
    const setoresData = [];

    setoresContainer.querySelectorAll('.setor-card').forEach((card)=>{
        const nomeSetor = card.textContent;
        const corSetor = card.style.backgroundColor;
        const letra = card.dataset.letra;

        const grids = [];
        for(const key in gridState){
            if(gridState[key] === letra){
                const [x, y] = key.split('_').map(Number);
                grids.push({x, y});
            }
        }

        if(grids.length > 0){
            setoresData.push({nomeSetor, corSetor, grids});
        }
    });

    if(setoresData.length === 0){
        alert('Nenhum grid atribuído aos setores.');
        return;
    }

    const idEstacionamento = parseInt(document.getElementById('estacionamentoSelect').value);
    if(isNaN(idEstacionamento)){
        alert('Estacionamento inválido.');
        return;
    }

    fetch("{{ route('estacionamentos.salvarSetores') }}", {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            idEstacionamento: idEstacionamento,
            setores: setoresData
        })
    })
    .then(async res => {
        let data;
        try {
            data = await res.json();
        } catch (err) {
            throw new Error('Resposta inválida do servidor.');
        }

        if(res.ok && data.success){
            alert(data.message);

            // 🚀 redireciona para a nova view de vagas
            window.location.href = `/estacionamentos/${idEstacionamento}/vagas`;

        } else if(data.errors){
            alert(Object.values(data.errors).flat().join('\n'));
        } else if(data.message){
            alert(data.message);
        } else {
            alert('Erro desconhecido ao salvar setores.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erro ao salvar setores: ' + err.message);
    });
});
</script>
@endsection
