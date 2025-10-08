<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->helper('form'); ?>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Novo Cliente</h2>
    <?= form_open_multipart('cliente/store') ?>

    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control <?= form_error('nome') ? 'is-invalid' : '' ?>" value="<?= set_value('nome') ?>">
        <?= form_error('nome', '<div class="invalid-feedback">', '</div>') ?>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" value="<?= set_value('email') ?>">
        <?= form_error('email', '<div class="invalid-feedback">', '</div>') ?>
    </div>

    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input 
            type="text" 
            name="telefone" 
            id="telefone"
            class="form-control <?= form_error('telefone') ? 'is-invalid' : '' ?>" 
            value="<?= set_value('telefone') ?>" 
            required 
            pattern="(\(\d{2}\)\s?\d{4,5}-\d{4}|[0-9]{10,11})"
            title="Insira um telefone válido com DDD, apenas números.">
        <?= form_error('telefone', '<div class="invalid-feedback">', '</div>') ?>
    </div>

    <div class="mb-3">
        <label for="imagem" class="form-label">Imagem</label>
        <input type="file" name="imagem" class="form-control">
    </div>

    <h4>Endereço</h4>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" id="cep" name="cep" class="form-control" placeholder="Digite o CEP">
        </div>
        <div class="col-md-6 mb-3">
            <label for="logradouro" class="form-label">Logradouro</label>
            <input type="text" id="logradouro" name="logradouro" class="form-control">
        </div>
        <div class="col-md-3 mb-3">
            <label for="numero" class="form-label">Número</label>
            <input type="text" id="numero" name="numero" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <label for="complemento" class="form-label">Complemento</label>
            <input type="text" id="complemento" name="complemento" class="form-control"> 
        </div>
        <div class="col-md-6 mb-3">
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" id="bairro" name="bairro" class="form-control">  
            </div>
        <div class="col-md-6 mb-3"> 
            <label for="localidade" class="form-label">Cidade</label>
            <input type="text" id="localidade" name="localidade" class="form-control">
            </div>
        <div class="col-md-6 mb-3"> 
            <label for="uf" class="form-label">Estado (UF)</label>
            <input type="text" id="uf" name="uf" class="form-control" maxlength="2">
        </div> 
    </div>    
    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Salvar</button>
    <a href="<?= site_url('cliente') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    <?= form_close() ?>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#telefone').mask('(00) 00000-0000');
    });
</script>

<script>
document.getElementById('cep').addEventListener('blur', function () {
    let cep = this.value.replace(/\D/g, '');
    if (cep.length === 8) {
        fetch("<?= site_url('cliente/consultaCep') ?>", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "cep=" + cep
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            document.getElementById('logradouro').value = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro || '';
            // o input na view usa id="localidade"
            var localidadeEl = document.getElementById('localidade') || document.getElementById('cidade');
            if (localidadeEl) localidadeEl.value = data.localidade || '';
            document.getElementById('uf').value = data.uf || '';
        })
        .catch(err => console.error(err));
    }
});
</script>
</body>
</html>
