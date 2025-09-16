@csrf

@if(isset($projeto))
    @method('PUT')
@endif

<div class="mb-3">
    <label for="nomeProjeto" class="form-label">Nome do Projeto</label>
    <input type="text" class="form-control" id="nomeProjeto" name="nomeProjeto"
           value="{{ old('nomeProjeto', $projeto->nomeProjeto ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="nomeFantasia" class="form-label">Nome Fantasia</label>
    <input type="text" class="form-control" id="nomeFantasia" name="nomeFantasia"
           value="{{ old('nomeFantasia', $projeto->nomeFantasia ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="razaoSocial" class="form-label">Razão Social</label>
    <input type="text" class="form-control" id="razaoSocial" name="razaoSocial"
           value="{{ old('razaoSocial', $projeto->razaoSocial ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="contatoNome" class="form-label">Nome do Contato</label>
    <input type="text" class="form-control" id="contatoNome" name="contatoNome"
           value="{{ old('contatoNome', $projeto->contatoNome ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="contatoCelular" class="form-label">Celular do Contato</label>
    <input type="text" class="form-control" id="contatoCelular" name="contatoCelular"
           value="{{ old('contatoCelular', $projeto->contatoCelular ?? '') }}">
</div>

<div class="mb-3">
    <label for="contatoEmail" class="form-label">E-mail do Contato</label>
    <input type="email" class="form-control" id="contatoEmail" name="contatoEmail"
           value="{{ old('contatoEmail', $projeto->contatoEmail ?? '') }}">
</div>

<h4>Endereço</h4>

<div class="mb-3">
    <label for="enderecoLogradouro" class="form-label">Logradouro</label>
    <input type="text" class="form-control" id="enderecoLogradouro" name="enderecoLogradouro"
           value="{{ old('enderecoLogradouro', $projeto->enderecoLogradouro ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="enderecoNumero" class="form-label">Número</label>
    <input type="text" class="form-control" id="enderecoNumero" name="enderecoNumero"
           value="{{ old('enderecoNumero', $projeto->enderecoNumero ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="enderecoComplemento" class="form-label">Complemento</label>
    <input type="text" class="form-control" id="enderecoComplemento" name="enderecoComplemento"
           value="{{ old('enderecoComplemento', $projeto->enderecoComplemento ?? '') }}">
</div>

<div class="mb-3">
    <label for="enderecoBairro" class="form-label">Bairro</label>
    <input type="text" class="form-control" id="enderecoBairro" name="enderecoBairro"
           value="{{ old('enderecoBairro', $projeto->enderecoBairro ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="enderecoCidade" class="form-label">Cidade</label>
    <input type="text" class="form-control" id="enderecoCidade" name="enderecoCidade"
           value="{{ old('enderecoCidade', $projeto->enderecoCidade ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="enderecoEstado" class="form-label">Estado (UF)</label>
    <input type="text" class="form-control" id="enderecoEstado" name="enderecoEstado"
           value="{{ old('enderecoEstado', $projeto->enderecoEstado ?? '') }}" maxlength="2" required>
</div>

<div class="mb-3">
    <label for="enderecoCEP" class="form-label">CEP</label>
    <input type="text" class="form-control" id="enderecoCEP" name="enderecoCEP"
           value="{{ old('enderecoCEP', $projeto->enderecoCEP ?? '') }}" required>
</div>
