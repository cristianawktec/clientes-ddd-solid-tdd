<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4"><i class="fas fa-check-circle"></i> <?= $titulo ?></h1>
        
        <div class="alert alert-info">
            <strong>Versão:</strong> <?= $versao ?><br>
            <strong>Verificado em:</strong> <?= $timestamp ?>
        </div>

        <div class="row">
            <?php foreach ($testes as $nome => $teste): ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header <?= $teste['status'] ? 'bg-success text-white' : 'bg-danger text-white' ?>">
                            <i class="fas <?= $teste['status'] ? 'fa-check' : 'fa-times' ?>"></i>
                            <?= ucfirst(str_replace('_', ' ', $nome)) ?>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= $teste['msg'] ?></p>
                            <?php if (isset($teste['arquivo'])): ?>
                                <small class="text-muted">Arquivo: <?= $teste['arquivo'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-4">
            <h3>Resultado Geral</h3>
            <?php
            $total = count($testes);
            $aprovados = array_sum(array_column($testes, 'status'));
            $porcentagem = round(($aprovados / $total) * 100);
            ?>
            
            <div class="progress mb-3">
                <div class="progress-bar <?= $porcentagem === 100 ? 'bg-success' : ($porcentagem > 50 ? 'bg-warning' : 'bg-danger') ?>" 
                     style="width: <?= $porcentagem ?>%">
                    <?= $aprovados ?>/<?= $total ?> (<?= $porcentagem ?>%)
                </div>
            </div>
            
            <?php if ($porcentagem === 100): ?>
                <div class="alert alert-success">
                    <h4><i class="fas fa-thumbs-up"></i> Todas as atualizações foram aplicadas com sucesso!</h4>
                    <p>O chatbot deve estar funcionando corretamente no servidor.</p>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <h4><i class="fas fa-exclamation-triangle"></i> Algumas atualizações não foram aplicadas</h4>
                    <p>Verifique os itens marcados em vermelho e faça upload dos arquivos correspondentes.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-4">
            <h3>Próximos Passos</h3>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="<?= site_url('cliente') ?>" class="btn btn-primary">
                        <i class="fas fa-list"></i> Testar Sistema Principal
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="<?= site_url('chatbot/test') ?>" class="btn btn-info">
                        <i class="fas fa-robot"></i> Testar API do Chatbot
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="<?= site_url('chatbot-test') ?>" class="btn btn-warning">
                        <i class="fas fa-vial"></i> Página de Testes do Chatbot
                    </a>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>