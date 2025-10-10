<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Lista de Clientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">📋 Lista de Clientes</h2>

    <a href="<?= site_url('cliente/create') ?>" class="btn btn-primary mb-3">➕ Novo Cliente</a>

    <form method="get" action="<?= site_url('cliente/index') ?>" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="nome" value="<?= $this->input->get('nome') ?>" placeholder="Nome" class="form-control">
    </div>
    <div class="col-md-3">
        <input type="text" name="email" value="<?= $this->input->get('email') ?>" placeholder="Email" class="form-control">
    </div>
    <div class="col-md-2">
        <input type="text" name="telefone" value="<?= $this->input->get('telefone') ?>" placeholder="Telefone" class="form-control">
    </div>
    <div class="col-md-2">
        <select name="uf" class="form-select">
            <option value="">-- UF --</option>
            <?php foreach (['SC','RS','PR','SP','RJ','MG'] as $uf): ?>
                <option value="<?= $uf ?>" <?= $this->input->get('uf') == $uf ? 'selected' : '' ?>><?= $uf ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>
</form>


    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark text-center">
        <tr>
          <th>Imagem</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($clientes)) : ?>
          <?php foreach ($clientes as $c) : ?>
            <?php
              // normaliza o item para array e evita warnings se chaves estiverem ausentes
              if (is_array($c)) {
                  $row = $c;
              } elseif (is_object($c) && method_exists($c, 'toArray')) {
                  $row = $c->toArray();
              } else {
                  $row = (array) $c;
              }

              $img = $row['imagem'] ?? null;
              $nome = $row['nome'] ?? '';
              $email = $row['email'] ?? '';
              $telefone = $row['telefone'] ?? '';
              $id = $row['id'] ?? '';

              // imagem: aceita tanto 'uploads/xxx.png' quanto só 'xxx.png'
              if (!empty($img)) {
                  $imgPath = htmlspecialchars($img);
                  if (strpos($imgPath, 'uploads/') !== 0) {
                      $imgPath = 'uploads/' . $imgPath;
                  }
              }
            ?>
            <tr>
              <td class="text-center">
                <?php if (!empty($img)): ?>
                  <img src="<?= base_url($imgPath) ?>" 
                       alt="Foto de <?= htmlspecialchars($nome) ?>" 
                       class="img-thumbnail" width="60">
                <?php else: ?>
                  <span class="text-muted">Sem imagem</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($nome) ?></td>
              <td><?= htmlspecialchars($email) ?></td>
              <td><?= htmlspecialchars($telefone) ?></td>
              <td class="text-center">
                <a href="<?= site_url('cliente/edit/' . $id) ?>" class="btn btn-sm btn-warning">
                  ✏️ Editar
                </a>
                <a href="<?= site_url('cliente/delete/' . $id) ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                  🗑️ Excluir
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="5" class="text-center text-muted">
              Nenhum cliente cadastrado.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Widget do Chatbot -->
  <?php $this->load->view('chatbot/widget-simple'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
