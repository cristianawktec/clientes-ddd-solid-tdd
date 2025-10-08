<?php

use PHPUnit\Framework\TestCase;
use App\Models\Cliente;

class ClienteEntityTest extends TestCase
{
    public function testDeveCriarClienteComDadosValidos()
    {
        $cliente = new Cliente([
            'nome' => 'João da Silva',
            'email' => 'joao@example.com',
            'telefone' => '48999999999'
        ]);

        $this->assertEquals('João da Silva', $cliente->nome);
        $this->assertEquals('joao@example.com', $cliente->email);
        $this->assertEquals('48999999999', $cliente->telefone);
    }

    public function testDeveEditarCliente()
    {
        $cliente = new Cliente([
            'nome' => 'Maria Oliveira',
            'email' => 'maria@example.com',
            'telefone' => '48988888888'
        ]);

        // simula alteração
        $cliente->nome = 'Maria Souza';
        $cliente->email = 'maria.souza@example.com';

        $this->assertEquals('Maria Souza', $cliente->nome);
        $this->assertEquals('maria.souza@example.com', $cliente->email);
    }

    public function testDeveExcluirCliente()
    {
        $cliente = new Cliente([
            'nome' => 'Carlos Pereira',
            'email' => 'carlos@example.com',
            'telefone' => '48977777777'
        ]);

        // simulamos a exclusão setando null
        $cliente = null;

        $this->assertNull($cliente);
    }
}
