<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CepService
{
    private $ci;
    private $apiUrl = 'https://viacep.com.br/ws/';

    public function __construct()
    {
        $this->ci =& get_instance();
        // garante que model de ceps existe
        $this->ci->load->model('CepModel');
    }

    /**
     * Retorna array com dados do endereço ou null.
     * Estratégia: primeiro procura no DB (CepModel). Se não achar, consulta ViaCEP e insere no DB.
     */
    public function buscar(string $cep): ?array
    {
        $cep = preg_replace('/\D/', '', $cep);
        if (strlen($cep) !== 8) {
            return null;
        }

        // consulta local (usa o método existente getByCep que retorna row_array)
        $res = $this->ci->CepModel->getByCep($cep);
        if ($res && is_array($res)) {
            return [
                'cep' => $res['cep'] ?? $cep,
                'logradouro' => $res['logradouro'] ?? null,
                'complemento' => $res['complemento'] ?? null,
                'bairro' => $res['bairro'] ?? null,
                'localidade' => $res['localidade'] ?? null,
                'uf' => $res['uf'] ?? null,
                'ibge' => $res['ibge'] ?? null,
                'gia' => $res['gia'] ?? null
            ];
        }

        // consulta via API externa
        $url = $this->apiUrl . $cep . '/json/';
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: PHP\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        if (!$response) return null;

        $dados = json_decode($response, true);
        if (isset($dados['erro']) && $dados['erro'] === true) {
            return null;
        }

        $insert = [
            'cep' => $cep,
            'logradouro' => $dados['logradouro'] ?? null,
            'complemento' => $dados['complemento'] ?? null,
            'bairro' => $dados['bairro'] ?? null,
            'localidade' => $dados['localidade'] ?? null,
            'uf' => $dados['uf'] ?? null,
            'ibge' => $dados['ibge'] ?? null,
            'gia' => $dados['gia'] ?? null
        ];

        // tenta inserir no DB para cache local (se model permitir)
        $this->ci->CepModel->inserir($insert);

        return $insert;
    }
}
