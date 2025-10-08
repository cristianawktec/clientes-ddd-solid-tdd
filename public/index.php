<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Repositories\ClienteRepositoryMysql;
use App\Service\ClienteService;

// Configuração simples do banco (pode jogar para .env depois)
$pdo = new PDO("mysql:host=localhost;dbname=clientes_db", "root", "");

// Injeta dependências
$repository = new ClienteRepositoryMysql($pdo);
$service = new ClienteService($repository);

// Router simples para teste
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $clientes = $service->listarTodos();
        header('Content-Type: application/json');
        echo json_encode($clientes);
        break;

    case 'view':
        $id = (int)($_GET['id'] ?? 0);
        $cliente = $service->buscarPorId($id);
        header('Content-Type: application/json');
        echo json_encode($cliente);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada']);
}
