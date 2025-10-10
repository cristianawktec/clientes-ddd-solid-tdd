<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Teste do Chatbot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-robot"></i> Teste do Chatbot IA</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Instruções de Teste</h5>
                            <p>Este é um ambiente de teste para o chatbot integrado com IA. Você pode:</p>
                            <ul>
                                <li>Fazer perguntas sobre o sistema de clientes</li>
                                <li>Solicitar informações sobre funcionalidades</li>
                                <li>Testar a integração com a API do Gemini</li>
                                <li>Verificar se as respostas estão sendo geradas corretamente</li>
                            </ul>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Status da Conexão</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="connection-status" class="d-flex align-items-center">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            <span>Verificando conexão...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Configuração da API</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="api-status" class="d-flex align-items-center">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            <span>Verificando API...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="testConnection()">
                                <i class="fas fa-play"></i> Testar Conexão
                            </button>
                            <button class="btn btn-success" onclick="testMessage()">
                                <i class="fas fa-comments"></i> Testar Mensagem
                            </button>
                            <a href="<?= site_url('cliente') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar ao Sistema
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <h5>Log de Teste</h5>
                            <div id="test-log" class="bg-light p-3 border rounded" style="height: 300px; overflow-y: auto;">
                                <p class="text-muted">Aguardando testes...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget do Chatbot para teste -->
    <?php $this->load->view('chatbot/widget'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        checkApiStatus();
    });

    function addLog(message, type = 'info') {
        const logContainer = document.getElementById('test-log');
        const time = new Date().toLocaleTimeString();
        const alertClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
        
        const logEntry = document.createElement('div');
        logEntry.className = `alert ${alertClass} alert-sm mb-2`;
        logEntry.innerHTML = `<small><strong>${time}:</strong> ${message}</small>`;
        
        logContainer.appendChild(logEntry);
        logContainer.scrollTop = logContainer.scrollHeight;
    }

    async function checkApiStatus() {
        try {
            addLog('Verificando configuração da API...', 'info');
            
            const response = await fetch('<?= base_url() ?>chatbot/test', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('api-status').innerHTML = `
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <span class="text-success">API Configurada</span>
                `;
                addLog('✅ API do Gemini configurada corretamente', 'success');
            } else {
                document.getElementById('api-status').innerHTML = `
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    <span class="text-warning">API Não Configurada</span>
                `;
                addLog('⚠️ ' + (data.message || 'API não configurada'), 'error');
            }
        } catch (error) {
            document.getElementById('api-status').innerHTML = `
                <i class="fas fa-times-circle text-danger me-2"></i>
                <span class="text-danger">Erro de Conexão</span>
            `;
            addLog('❌ Erro ao verificar API: ' + error.message, 'error');
        }
    }

    async function testConnection() {
        try {
            addLog('Testando conexão com o chatbot...', 'info');
            
            const response = await fetch('<?= base_url() ?>chatbot/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('connection-status').innerHTML = `
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <span class="text-success">Conectado</span>
                `;
                addLog('✅ Conexão estabelecida com sucesso. Session ID: ' + data.session_id, 'success');
            } else {
                document.getElementById('connection-status').innerHTML = `
                    <i class="fas fa-times-circle text-danger me-2"></i>
                    <span class="text-danger">Falha na Conexão</span>
                `;
                addLog('❌ Falha na conexão: ' + (data.message || 'Erro desconhecido'), 'error');
            }
        } catch (error) {
            document.getElementById('connection-status').innerHTML = `
                <i class="fas fa-times-circle text-danger me-2"></i>
                <span class="text-danger">Erro de Rede</span>
            `;
            addLog('❌ Erro de rede: ' + error.message, 'error');
        }
    }

    async function testMessage() {
        try {
            addLog('Enviando mensagem de teste...', 'info');
            
            // Primeiro, iniciar uma sessão se não existir
            const sessionResponse = await fetch('<?= base_url() ?>chatbot/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            const sessionData = await sessionResponse.json();
            
            if (!sessionData.success) {
                throw new Error('Não foi possível iniciar sessão');
            }
            
            addLog('📝 Sessão iniciada, enviando mensagem...', 'info');
            
            // Enviar mensagem de teste
            const messageResponse = await fetch('<?= base_url() ?>chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: 'Olá! Este é um teste do chatbot. Você pode me explicar como funciona o sistema de clientes?',
                    session_id: sessionData.session_id
                })
            });
            
            const messageData = await messageResponse.json();
            
            if (messageData.success) {
                addLog('✅ Mensagem processada com sucesso!', 'success');
                addLog('🤖 Resposta da IA: ' + messageData.response.substring(0, 100) + '...', 'success');
            } else {
                addLog('❌ Falha ao processar mensagem: ' + (messageData.message || 'Erro desconhecido'), 'error');
            }
        } catch (error) {
            addLog('❌ Erro no teste de mensagem: ' + error.message, 'error');
        }
    }
    </script>
</body>
</html>