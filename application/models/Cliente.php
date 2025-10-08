<?php
namespace App\Models;

use App\Domain\Entities\ClienteEntity;

class Cliente
{
    public $nome;
    public $email;
    public $telefone;

    /**
     * Construtor opcional com array associativo.
     */
    public function __construct(array $data = [])
    {
        $this->nome     = $data['nome']     ?? null;
        $this->email    = $data['email']    ?? null;
        $this->telefone = $data['telefone'] ?? null;
    }

    /**
     * Converte para ClienteEntity (camada de domínio).
     */
    public function toEntity(): ClienteEntity
    {
        return new ClienteEntity([
            'nome'     => $this->nome,
            'email'    => $this->email,
            'telefone' => $this->telefone
        ]);
    }
}
