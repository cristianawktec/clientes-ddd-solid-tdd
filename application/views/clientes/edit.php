<!DOCTYPE html>
<html>
<head>
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Editar Cliente</h2>

    <form action="<?= site_url('cliente/update/' . $cliente['id']) ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($cliente['nome']) ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($cliente['email']) ?>">
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone:</label>
            <input type="text" name="telefone" id="telefone" class="form-control" value="<?= htmlspecialchars($cliente['telefone']) ?>">
        </div>

        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem:</label>
            <input type="file" name="imagem" id="imagem" class="form-control">
            <?php if (!empty($cliente['imagem'])): ?>
                <img src="<?= base_url('uploads/' . $cliente['imagem']) ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>




<h4 class="mt-4">Endereço</h4>
<div class="row">
  <div class="col-md-3 mb-3">
    <label for="cep" class="form-label">CEP:</label>
    <input type="text" name="cep" id="cep" class="form-control" 
           value="<?= htmlspecialchars($cliente['cep'] ?? '') ?>">
  </div>
  <div class="col-md-6 mb-3">
    <label for="logradouro" class="form-label">Logradouro:</label>
    <input type="text" name="logradouro" id="logradouro" class="form-control"
           value="<?= htmlspecialchars($cliente['logradouro'] ?? '') ?>">
  </div>
  <div class="col-md-3 mb-3">
    <label for="numero" class="form-label">Número:</label>
    <input type="text" name="numero" id="numero" class="form-control"
           value="<?= htmlspecialchars($cliente['numero'] ?? '') ?>">
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label for="bairro" class="form-label">Bairro:</label>
    <input type="text" name="bairro" id="bairro" class="form-control"
           value="<?= htmlspecialchars($cliente['bairro'] ?? '') ?>">
  </div>
  <div class="col-md-4 mb-3">
    <label for="localidade" class="form-label">Cidade:</label>
    <input type="text" name="localidade" id="localidade" class="form-control"
           value="<?= htmlspecialchars($cliente['localidade'] ?? '') ?>">
  </div>
  <div class="col-md-2 mb-3">
    <label for="uf" class="form-label">UF:</label>
    <input type="text" name="uf" id="uf" maxlength="2" class="form-control"
           value="<?= htmlspecialchars($cliente['uf'] ?? '') ?>">
  </div>
  <div class="col-md-2 mb-3">
    <label for="complemento" class="form-label">Complemento:</label>
    <input type="text" name="complemento" id="complemento" class="form-control"
           value="<?= htmlspecialchars($cliente['complemento'] ?? '') ?>">
  </div>
</div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= site_url('cliente') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const telefoneInput = document.getElementById("telefone");

  telefoneInput.addEventListener("input", function (e) {
    // Remove tudo que não for número
    let value = e.target.value.replace(/\D/g, ""); 

    if (value.length > 11) {
      // Limita a 11 dígitos
      value = value.slice(0, 11); 
    }

    // Aplica máscara (99) 99999-9999 ou (99) 9999-9999 conforme o tamanho
    if (value.length <= 10) {
      value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
    } else {
      value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3");
    }

    e.target.value = value.trim();
  });
});


$("#cep").blur(function(){
  var cep = $(this).val().replace(/\D/g, '');
  if(cep.length == 8){
    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/", function(dados) {
      if (!("erro" in dados)) {
        $("#logradouro").val(dados.logradouro);
        $("#bairro").val(dados.bairro);
        $("#cidade").val(dados.localidade);
        $("#uf").val(dados.uf);
      } else {
        alert("CEP não encontrado.");
      }
    });
  }
});
</script>
</body>
</html>
