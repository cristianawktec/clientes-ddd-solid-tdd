<?php

namespace App\Models;

class Cliente
{
    private ?int $id;
    private string $nome;
    private string $email;

    public function __construct(?int $id, string $nome, string $email)
    {
        $this->id    = $id;
        $this->nome  = $nome;
        $this->email = $email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
