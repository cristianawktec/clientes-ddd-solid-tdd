<?php

namespace App\Domain\Entities;

use InvalidArgumentException;

class ClienteEntity
{
    private $id;
    private $cpf;
    private $nome;
    private $email;
    private $telefone;
    private $imagem;
    private $endereco; // array com keys cep, logradouro, bairro, localidade, uf, complemento

    /**
     * Construtor flexível.
     * Aceita um array associativo ou argumentos posicionais: (nome, cpf, email)
     *
     * Exemplos:
     * new ClienteEntity(['nome'=>..., 'cpf'=>..., 'email'=>...])
     * new ClienteEntity('João', '12345678901', 'joao@email.com')
     *
     * @param mixed $data
     */
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->setNome($data['nome'] ?? null);
            $this->setCpf($data['cpf'] ?? null);
            $this->setEmail($data['email'] ?? null);
            $this->setTelefone($data['telefone'] ?? null);
            $this->imagem = $data['imagem'] ?? null;
            $this->endereco = $data['endereco'] ?? null;
            return;
        }

        // Se vierem argumentos posicionais: nome, cpf, email
        $args = func_get_args();
        $nome = $args[0] ?? null;
        $cpf = $args[1] ?? null;
        $email = $args[2] ?? null;

        $this->id = null;
        $this->setNome($nome);
        $this->setCpf($cpf);
        $this->setEmail($email);
        $this->telefone = null;
        $this->imagem = null;
        $this->endereco = null;
    }

    public function setNome($nome)
    {
        if (empty($nome) || strlen(trim($nome)) < 3) {
            throw new InvalidArgumentException('Nome é obrigatório e deve ter ao menos 3 caracteres.');
        }
        $this->nome = trim($nome);
    }

    public function setCpf($cpf)
    {
        if ($cpf === null) {
            $this->cpf = null;
            return;
        }
        $clean = preg_replace('/\D/', '', $cpf);
        // validação simples: 11 dígitos numéricos (não implementa algoritmo de verificação)
        if (empty($clean) || !preg_match('/^[0-9]{11}$/', $clean)) {
            throw new InvalidArgumentException('CPF inválido. Deve conter 11 dígitos numéricos.');
        }
        $this->cpf = $clean;
    }

    public function setEmail($email)
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email inválido.');
        }
        $this->email = $email;
    }

    public function setTelefone($telefone)
    {
        // aceitarmos telefone nulo (nem todo cliente tem telefone cadastrado)
        if ($telefone === null) {
            $this->telefone = null;
            return;
        }

        $clean = preg_replace('/\D/', '', $telefone);
        if (empty($clean) || !preg_match('/^[0-9]{10,11}$/', $clean)) {
            throw new InvalidArgumentException('Telefone inválido. Use somente números (10-11 dígitos).');
        }
        $this->telefone = $clean;
    }

    public function setImagem($fileName)
    {
        $this->imagem = $fileName;
    }

    public function setEndereco(array $endereco)
    {
        $this->endereco = $endereco;
    }

    // getters
    public function getId() { return $this->id; }
    public function getCpf() { return $this->cpf; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getTelefone() { return $this->telefone; }
    public function getImagem() { return $this->imagem; }
    public function getEndereco() { return $this->endereco; }

    // normaliza pra salvar no DB via repository
    public function toArray(): array
    {
        $arr = [
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'email' => $this->getEmail(),
            'telefone' => $this->getTelefone(),
            'imagem' => $this->getImagem()
        ];

        if (!empty($this->endereco) && is_array($this->endereco)) {
            // se quiser persistir campos do endereço no próprio cliente (opcional)
            $arr = array_merge($arr, [
                'cep' => $this->endereco['cep'] ?? null,
                'logradouro' => $this->endereco['logradouro'] ?? null,
                'numero' => $this->endereco['numero'] ?? null,
                'complemento' => $this->endereco['complemento'] ?? null,
                'bairro' => $this->endereco['bairro'] ?? null,
                'cidade' => $this->endereco['localidade'] ?? null,
                'uf' => $this->endereco['uf'] ?? null,
            ]);
        }

        return $arr;
    }
}
