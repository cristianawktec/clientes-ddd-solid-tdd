<?php
// Arquivo de teste para verificar se atualizações foram aplicadas
defined('BASEPATH') OR exit('No direct script access allowed');

class VerificarAtualizacao extends CI_Controller {

    public function index() {
        $this->load->helper('url');
        
        $testes = [
            'font_awesome' => $this->verificarFontAwesome(),
            'widget_simple' => $this->verificarWidget(),
            'chatbot_controller' => $this->verificarChatbot(),
            'database_config' => $this->verificarDatabase()
        ];
        
        $data = [
            'titulo' => 'Verificação de Atualizações - Hostgator',
            'testes' => $testes,
            'timestamp' => date('Y-m-d H:i:s'),
            'versao' => '2025-10-09-v2'
        ];
        
        $this->load->view('verificacao_atualizacao', $data);
    }
    
    private function verificarFontAwesome() {
        $arquivo = APPPATH . 'views/clientes/index.php';
        if (!file_exists($arquivo)) return ['status' => false, 'msg' => 'Arquivo index.php não encontrado'];
        
        $conteudo = file_get_contents($arquivo);
        $temFontAwesome = strpos($conteudo, 'font-awesome') !== false;
        
        return [
            'status' => $temFontAwesome,
            'msg' => $temFontAwesome ? 'Font Awesome encontrado' : 'Font Awesome NÃO encontrado',
            'arquivo' => $arquivo
        ];
    }
    
    private function verificarWidget() {
        $arquivo = APPPATH . 'views/chatbot/widget-simple.php';
        $existe = file_exists($arquivo);
        
        return [
            'status' => $existe,
            'msg' => $existe ? 'Widget-simple.php existe' : 'Widget-simple.php NÃO existe',
            'arquivo' => $arquivo
        ];
    }
    
    private function verificarChatbot() {
        $arquivo = APPPATH . 'controllers/Chatbot.php';
        if (!file_exists($arquivo)) return ['status' => false, 'msg' => 'Chatbot.php não encontrado'];
        
        $conteudo = file_get_contents($arquivo);
        $temAPI = strpos($conteudo, 'AIzaSyCmgb11bxDwuT4B2trEm8v_hVfdXHiXYFI') !== false;
        
        return [
            'status' => $temAPI,
            'msg' => $temAPI ? 'API Key encontrada no controlador' : 'API Key NÃO encontrada',
            'arquivo' => $arquivo
        ];
    }
    
    private function verificarDatabase() {
        $arquivo = APPPATH . 'config/database.php';
        if (!file_exists($arquivo)) return ['status' => false, 'msg' => 'Database.php não encontrado'];
        
        $conteudo = file_get_contents($arquivo);
        $temHostgator = strpos($conteudo, 'br908.hostgator.com.br') !== false;
        
        return [
            'status' => $temHostgator,
            'msg' => $temHostgator ? 'Configuração Hostgator ativa' : 'Configuração Hostgator NÃO ativa',
            'arquivo' => $arquivo
        ];
    }
}
?>