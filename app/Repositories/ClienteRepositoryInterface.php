<?php

namespace App\Repositories;

use App\Domain\Entities\ClienteEntity;

interface ClienteRepositoryInterface
{
    public function salvar(ClienteEntity $cliente): bool;
    public function atualizar(ClienteEntity $cliente): bool;
    public function excluir(int $id): bool;
    public function buscarPorId(int $id): ?ClienteEntity;
    public function listarTodos(array $filters = []): array; // array of ClienteEntity
}
