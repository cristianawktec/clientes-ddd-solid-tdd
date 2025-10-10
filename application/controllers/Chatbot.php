<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbot extends CI_Controller {

    private $gemini_api_key;

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url']);
        $this->load->library(['session']);
        
        // API Key do Google Gemini
        $this->gemini_api_key = 'AIzaSyCmgb11bxDwuT4B2trEm8v_hVfdXHiXYFI';
    }

    /**
     * Inicializa uma nova sessão de chatbot
     */
    public function start() {
        $session_id = 'chatbot_' . uniqid();
        
        $this->session->set_userdata([
            'chatbot_session_id' => $session_id,
            'chatbot_active' => true
        ]);

        $response = [
            'success' => true,
            'session_id' => $session_id,
            'message' => 'Sessão do chatbot iniciada com sucesso!'
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Processa mensagens do usuário com IA do Google Gemini
     */
    public function message() {
        $input = json_decode($this->input->raw_input_stream, true);
        $user_message = isset($input['message']) ? trim($input['message']) : '';

        if (empty($user_message)) {
            $response = [
                'success' => false,
                'message' => 'Mensagem não pode estar vazia'
            ];
        } else {
            try {
                $ai_response = $this->generateGeminiResponse($user_message);
                
                $response = [
                    'success' => true,
                    'response' => $ai_response,
                    'session_id' => $this->session->userdata('chatbot_session_id'),
                    'timestamp' => date('Y-m-d H:i:s')
                ];
            } catch (Exception $e) {
                // Se a IA falhar, usa resposta básica
                $response = [
                    'success' => true,
                    'response' => $this->getBasicResponse($user_message),
                    'session_id' => $this->session->userdata('chatbot_session_id'),
                    'timestamp' => date('Y-m-d H:i:s'),
                    'note' => 'Resposta básica (IA temporariamente indisponível)'
                ];
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Verifica o status do chatbot
     */
    public function test() {
        $response = [
            'success' => true,
            'message' => 'Chatbot funcionando com IA Google Gemini',
            'api_configured' => !empty($this->gemini_api_key),
            'api_key_present' => !empty($this->gemini_api_key),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Finaliza a sessão do chatbot
     */
    public function end() {
        $this->session->unset_userdata(['chatbot_session_id', 'chatbot_active']);

        $response = [
            'success' => true,
            'message' => 'Sessão finalizada com sucesso'
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Gera resposta usando a API do Google Gemini
     */
    private function generateGeminiResponse($user_message) {
        if (empty($this->gemini_api_key)) {
            throw new Exception('API Key não configurada');
        }

        $context = "Assistente virtual do sistema de clientes (PHP CodeIgniter 3).
Funcionalidades: cadastro, edição, exclusão de clientes, upload de fotos, busca CEP.
Responda de forma objetiva e amigável.";        $prompt = $context . "\n\nUsuário: " . $user_message . "\n\nAssistente:";

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
            'generationConfig' => [
                'temperature' => 0.5,
                'topK' => 20,
                'topP' => 0.8,
                'maxOutputTokens' => 150,
            ]
        ];

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $this->gemini_api_key;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
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
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception('Erro HTTP: ' . $http_code);
        }

        $decoded_response = json_decode($response, true);

        if (!$decoded_response || !isset($decoded_response['candidates'][0]['content']['parts'][0]['text'])) {
            throw new Exception('Resposta inválida da API');
        }

        return trim($decoded_response['candidates'][0]['content']['parts'][0]['text']);
    }

    /**
     * Resposta básica caso a IA falhe
     */
    private function getBasicResponse($user_message) {
        $simple_responses = [
            'olá' => 'Olá! Como posso ajudá-lo com o sistema de clientes?',
            'oi' => 'Oi! Como posso ajudá-lo com o sistema de clientes?',
            'help' => 'Posso ajudá-lo com: cadastro de clientes, edição, exclusão, upload de fotos e consultas.',
            'ajuda' => 'Posso ajudá-lo com: cadastro de clientes, edição, exclusão, upload de fotos e consultas.',
            'sistema' => 'Este é um sistema de gerenciamento de clientes desenvolvido em PHP com CodeIgniter 3.',
            'teste' => 'Sistema funcionando perfeitamente! Como posso ajudá-lo?',
            'funciona' => 'Sim, estou funcionando! Como posso ajudá-lo com o sistema de clientes?'
        ];
        
        $lower_message = strtolower($user_message);
        
        // Verifica se há uma resposta específica
        foreach ($simple_responses as $key => $response_text) {
            if (strpos($lower_message, $key) !== false) {
                return $response_text;
            }
        }
        
        return 'Obrigado pela sua mensagem: "' . $user_message . '". Como posso ajudá-lo com o sistema de clientes? Posso explicar sobre cadastro, edição, exclusão ou outras funcionalidades.';
    }
}