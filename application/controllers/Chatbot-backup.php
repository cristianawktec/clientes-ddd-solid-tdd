<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'infrastructure/services/GeminiService.php');

class Chatbot extends CI_Controller {

    private $geminiService;

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'text']);
        $this->load->library(['session']);
        
        // Inicializa o serviço do Gemini
        $this->geminiService = new GeminiService();
    }

    /**
     * Inicializa uma nova sessão de chatbot
     */
    public function start() {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $session_id = 'chatbot_' . uniqid();
        
        // Armazena o contexto inicial na sessão
        $this->session->set_userdata([
            'chatbot_session_id' => $session_id,
            'chatbot_context' => 'Você é um assistente virtual especializado no sistema de gerenciamento de clientes. Você pode ajudar os usuários com informações sobre como cadastrar, editar, visualizar e excluir clientes do sistema. O sistema permite upload de fotos, validação de dados, integração com API de CEP (ViaCEP) e possui uma estrutura organizada seguindo padrões DDD.',
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
     * Processa mensagens do usuário e retorna resposta da IA
     */
    public function message() {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $input = json_decode($this->input->raw_input_stream, true);
        $user_message = isset($input['message']) ? trim($input['message']) : '';
        $session_id = isset($input['session_id']) ? $input['session_id'] : $this->session->userdata('chatbot_session_id');

        if (empty($user_message)) {
            $response = [
                'success' => false,
                'message' => 'Mensagem não pode estar vazia'
            ];
        } else if (!$this->session->userdata('chatbot_active')) {
            $response = [
                'success' => false,
                'message' => 'Sessão do chatbot não está ativa. Por favor, inicie uma nova sessão.'
            ];
        } else {
            try {
                $ai_response = $this->geminiService->generateChatbotResponse($user_message);
                
                $response = [
                    'success' => true,
                    'response' => $ai_response,
                    'session_id' => $session_id,
                    'timestamp' => date('Y-m-d H:i:s')
                ];
            } catch (Exception $e) {
                log_message('error', 'Chatbot Error: ' . $e->getMessage());
                
                $response = [
                    'success' => false,
                    'message' => 'Erro ao processar mensagem: ' . $e->getMessage()
                ];
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Verifica o status da API do Gemini
     */
    public function test() {
        if ($this->input->method() !== 'get') {
            show_404();
            return;
        }

        $response = [
            'success' => $this->geminiService->isConfigured(),
            'message' => $this->geminiService->isConfigured() ? 'API Key configurada' : 'API Key não configurada',
            'api_configured' => $this->geminiService->isConfigured(),
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
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $this->session->unset_userdata(['chatbot_session_id', 'chatbot_context', 'chatbot_active']);

        $response = [
            'success' => true,
            'message' => 'Sessão finalizada com sucesso'
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}