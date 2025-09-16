{{-- resources/views/partials/_formEstacionamento.blade.php --}}

<div id="formCampos">
    <div class="mb-3">
        <label for="idProjeto" class="form-label">Selecione o Projeto</label>
        <select name="idProjeto" id="idProjeto" class="form-control" required>
            <option value="">-- Escolha um Projeto --</option>
            @foreach($projetos as $projeto)
                <option value="{{ $projeto->idProjeto }}"
                    {{ (old('idProjeto', $estacionamento->idProjeto ?? '') == $projeto->idProjeto) ? 'selected' : '' }}>
                    {{ $projeto->nomeProjeto }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="gridsEixoX" class="form-label">Grids Eixo X</label>
        <input type="number" name="gridsEixoX" id="gridsEixoX" class="form-control"
               value="{{ old('gridsEixoX', $estacionamento->gridsEixoX ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="gridsEixoY" class="form-label">Grids Eixo Y</label>
        <input type="number" name="gridsEixoY" id="gridsEixoY" class="form-control"
               value="{{ old('gridsEixoY', $estacionamento->gridsEixoY ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="vagasCarro" class="form-label">Vagas Carro</label>
        <input type="number" name="vagasCarro" id="vagasCarro" class="form-control"
               value="{{ old('vagasCarro', $estacionamento->vagasCarro ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="vagasMoto" class="form-label">Vagas Moto</label>
        <input type="number" name="vagasMoto" id="vagasMoto" class="form-control"
               value="{{ old('vagasMoto', $estacionamento->vagasMoto ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="vagasPreferencial" class="form-label">Vagas Preferenciais</label>
        <input type="number" name="vagasPreferencial" id="vagasPreferencial" class="form-control"
               value="{{ old('vagasPreferencial', $estacionamento->vagasPreferencial ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="vagasPcd" class="form-label">Vagas PCD</label>
        <input type="number" name="vagasPcd" id="vagasPcd" class="form-control"
               value="{{ old('vagasPcd', $estacionamento->vagasPcd ?? '') }}" required>
    </div>

    <button type="submit" class="btn btn-dark mt-2">
        {{ $submitButtonText ?? 'Salvar Estacionamento' }}
    </button>
        <a href="{{ route('dashboard') }}" class="btn btn-dark mt-2">Voltar à Dashboard</a>
</div>

<script>
    document.getElementById('idProjeto').addEventListener('change', function () {
        let formCampos = document.getElementById('formCampos');
        formCampos.style.display = this.value ? 'block' : 'none';
    });
</script>
