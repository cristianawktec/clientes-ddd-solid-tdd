<?php
class Cliente {
    private $nome;
    private $email;

    public function __construct($nome, $email) {
        if (empty($nome)) {
            throw new Exception("Nome é obrigatório");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }
        $this->nome = $nome;
        $this->email = $email;
    }

    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
}
