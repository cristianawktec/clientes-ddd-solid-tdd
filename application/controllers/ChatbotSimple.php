<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbot extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url']);
        $this->load->library(['session']);
    }

    /**
     * Inicializa uma nova sessão de chatbot
     */
    public function start() {
        // Permite GET e POST para facilitar testes
        $session_id = 'chatbot_' . uniqid();
        
        // Armazena o contexto inicial na sessão
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
     * Processa mensagens do usuário - versão simplificada para testes
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
            // Resposta simples para testes (sem IA por enquanto)
            $simple_responses = [
                'olá' => 'Olá! Como posso ajudá-lo com o sistema de clientes?',
                'help' => 'Posso ajudá-lo com: cadastro de clientes, edição, exclusão e consultas.',
                'sistema' => 'Este é um sistema de gerenciamento de clientes desenvolvido em PHP com CodeIgniter.',
                'teste' => 'Sistema funcionando perfeitamente! Como posso ajudá-lo?'
            ];
            
            $lower_message = strtolower($user_message);
            $bot_response = 'Obrigado pela sua mensagem: "' . $user_message . '". Este é um chatbot de teste. Em breve terei integração com IA!';
            
            // Verifica se há uma resposta específica
            foreach ($simple_responses as $key => $response_text) {
                if (strpos($lower_message, $key) !== false) {
                    $bot_response = $response_text;
                    break;
                }
            }
            
            $response = [
                'success' => true,
                'response' => $bot_response,
                'session_id' => $this->session->userdata('chatbot_session_id'),
                'timestamp' => date('Y-m-d H:i:s')
            ];
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
            'message' => 'Chatbot funcionando (modo teste)',
            'api_configured' => false, // Por enquanto sem IA
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
}