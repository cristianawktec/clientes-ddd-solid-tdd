<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Serviço para integração com a API do Google Gemini
 */
class GeminiService
{
    private $api_key;
    private $api_url;
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->api_key = 'AIzaSyCmgb11bxDwuT4B2trEm8v_hVfdXHiXYFI';
        $this->api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    }

    /**
     * Verifica se a API está configurada
     */
    public function isConfigured(): bool
    {
        return !empty($this->api_key);
    }

    /**
     * Gera uma resposta usando o Gemini
     */
    public function generateResponse(string $prompt, array $config = []): array
    {
        if (!$this->isConfigured()) {
            throw new Exception('API Key do Gemini não configurada');
        }

        $defaultConfig = [
            'temperature' => 0.7,
            'topK' => 1,
            'topP' => 1,
            'maxOutputTokens' => 2048,
        ];

        $generationConfig = array_merge($defaultConfig, $config);

        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ],
            'generationConfig' => $generationConfig
        ];

        return $this->makeApiCall($data);
    }

    /**
     * Gera resposta para chatbot com contexto específico
     */
    public function generateChatbotResponse(string $userMessage, string $context = ''): string
    {
        $systemContext = $context ?: $this->getDefaultChatbotContext();
        
        $prompt = $systemContext . "\n\nUsuário: " . $userMessage . "\n\nAssistente:";

        $response = $this->generateResponse($prompt, [
            'temperature' => 0.8,
            'maxOutputTokens' => 1024
        ]);

        if (!$response['success']) {
            throw new Exception($response['error']);
        }

        return $response['text'];
    }

    /**
     * Contexto padrão para o chatbot
     */
    private function getDefaultChatbotContext(): string
    {
        return "Você é um assistente virtual especializado no sistema de gerenciamento de clientes desenvolvido em PHP CodeIgniter 3. 

FUNCIONALIDADES DO SISTEMA:
- Cadastro de clientes com dados pessoais (nome, email, telefone)
- Upload e gerenciamento de fotos de clientes
- Cadastro de endereços vinculados aos clientes
- Integração com API ViaCEP para busca automática de endereços
- Validações de formulário
- Listagem com paginação e filtros
- Edição e exclusão de clientes
- Estrutura DDD (Domain-Driven Design)
- Testes unitários com PHPUnit
- Padrão Repository e Service

TECNOLOGIAS UTILIZADAS:
- PHP 8.0+
- CodeIgniter 3
- MySQL/MariaDB
- Bootstrap 5
- jQuery
- PHPUnit para testes

Responda de forma clara, útil e específica sobre o sistema. Se não souber algo específico, seja honesto e sugira onde o usuário pode encontrar mais informações. Mantenha um tom profissional mas amigável.";
    }

    /**
     * Faz a chamada para a API do Gemini
     */
    private function makeApiCall(array $data): array
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->api_url . '?key=' . $this->api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'success' => false,
                'error' => 'Erro de cURL: ' . $curlError
            ];
        }

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'Erro HTTP: ' . $httpCode . ' - ' . $response
            ];
        }

        $decodedResponse = json_decode($response, true);

        if (!$decodedResponse) {
            return [
                'success' => false,
                'error' => 'Resposta JSON inválida da API'
            ];
        }

        if (isset($decodedResponse['error'])) {
            return [
                'success' => false,
                'error' => 'Erro da API: ' . $decodedResponse['error']['message']
            ];
        }

        if (!isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            return [
                'success' => false,
                'error' => 'Resposta inválida da API do Gemini'
            ];
        }

        return [
            'success' => true,
            'text' => trim($decodedResponse['candidates'][0]['content']['parts'][0]['text']),
            'raw_response' => $decodedResponse
        ];
    }

    /**
     * Testa a conectividade com a API
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'API Key não configurada'
            ];
        }

        try {
            $response = $this->generateResponse('Teste de conexão. Responda apenas "OK".');
            
            return [
                'success' => $response['success'],
                'message' => $response['success'] ? 'Conexão estabelecida com sucesso' : $response['error']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage()
            ];
        }
    }
}