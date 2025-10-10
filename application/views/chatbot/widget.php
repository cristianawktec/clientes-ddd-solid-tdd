<!-- Widget do Chatbot -->
<div id="chatbot-widget" class="chatbot-widget" style="display: none;">
    <div class="chatbot-header">
        <i class="fas fa-robot"></i>
        <span>Assistente Virtual</span>
        <button type="button" class="chatbot-close" onclick="toggleChatbot()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="chatbot-messages" id="chatbot-messages">
        <div class="message bot-message">
            <i class="fas fa-robot"></i>
            <div class="message-content">
                Olá! Sou seu assistente virtual. Como posso ajudá-lo com o sistema de clientes?
            </div>
        </div>
    </div>
    <div class="chatbot-input">
        <div class="input-group">
            <input type="text" id="chatbot-input" class="form-control" placeholder="Digite sua mensagem..." disabled>
            <button class="btn btn-primary" type="button" id="chatbot-send" onclick="sendMessage()" disabled>
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
        <small class="text-muted" id="chatbot-status">Conectando...</small>
    </div>
</div>

<!-- Botão flutuante para abrir o chatbot -->
<div class="chatbot-toggle" onclick="toggleChatbot()">
    <div class="chatbot-toggle-content">
        <i class="fas fa-robot"></i>
        <span class="chatbot-toggle-text">Chat IA</span>
    </div>
    <div class="chatbot-pulse"></div>
</div>

<style>
.chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    z-index: 1000;
    transition: all 0.3s ease;
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.6);
}

.chatbot-toggle-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.chatbot-toggle-content i {
    font-size: 20px;
    animation: bounce 2s infinite;
}

.chatbot-toggle-text {
    font-size: 10px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.chatbot-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    70% {
        transform: scale(1.4);
        opacity: 0;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-5px);
    }
    60% {
        transform: translateY(-3px);
    }
}

.chatbot-widget {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 350px;
    height: 450px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    z-index: 1001;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chatbot-header {
    background-color: #007bff;
    color: white;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chatbot-header i {
    font-size: 18px;
}

.chatbot-header span {
    flex-grow: 1;
    font-weight: 500;
}

.chatbot-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
}

.chatbot-close:hover {
    background-color: rgba(255,255,255,0.1);
}

.chatbot-messages {
    flex-grow: 1;
    padding: 15px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.message {
    display: flex;
    gap: 10px;
    max-width: 85%;
}

.message.user-message {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.message i {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
}

.bot-message i {
    background-color: #007bff;
    color: white;
}

.user-message i {
    background-color: #28a745;
    color: white;
}

.message-content {
    background-color: #f8f9fa;
    padding: 10px 12px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.4;
}

.user-message .message-content {
    background-color: #007bff;
    color: white;
}

.chatbot-input {
    padding: 15px;
    border-top: 1px solid #dee2e6;
}

.chatbot-input .input-group {
    margin-bottom: 5px;
}

.chatbot-input input {
    border-radius: 20px;
}

.chatbot-input button {
    border-radius: 20px;
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
}

.typing-dots {
    display: flex;
    gap: 3px;
}

.typing-dot {
    width: 6px;
    height: 6px;
    background-color: #6c757d;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .chatbot-widget {
        width: 90%;
        right: 5%;
        left: 5%;
        bottom: 80px;
    }
}
</style>

<script>
let chatbotSessionId = null;
let isConnected = false;

// Aguarda o carregamento completo da página
window.addEventListener('load', function() {
    // Pequeno delay para garantir que todos os elementos estão carregados
    setTimeout(function() {
        initializeChatbot();
        
        // Adiciona listener para Enter apenas se o elemento existir
        const chatInput = document.getElementById('chatbot-input');
        if (chatInput) {
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
    }, 100);
});

async function initializeChatbot() {
    try {
        // Habilita o input imediatamente para permitir teste
        enableChatbotInput();
        updateChatbotStatus('Conectando...', 'info');
        
        const response = await fetch('<?= base_url() ?>chatbot/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            chatbotSessionId = data.session_id;
            isConnected = true;
            updateChatbotStatus('Online', 'success');
        } else {
            updateChatbotStatus('Modo offline - Digite para testar', 'warning');
        }
    } catch (error) {
        console.error('Erro ao inicializar chatbot:', error);
        updateChatbotStatus('Offline - Digite para testar', 'warning');
    }
}

function toggleChatbot() {
    const widget = document.getElementById('chatbot-widget');
    if (!widget) return;
    
    if (widget.style.display === 'none' || widget.style.display === '') {
        widget.style.display = 'flex';
    } else {
        widget.style.display = 'none';
    }
}

function updateChatbotStatus(message, type) {
    const statusElement = document.getElementById('chatbot-status');
    if (!statusElement) return;
    
    statusElement.textContent = message;
    statusElement.className = 'text-muted';
    
    if (type === 'success') {
        statusElement.className = 'text-success';
    } else if (type === 'error') {
        statusElement.className = 'text-danger';
    } else if (type === 'warning') {
        statusElement.className = 'text-warning';
    } else if (type === 'info') {
        statusElement.className = 'text-info';
    }
}

function enableChatbotInput() {
    const input = document.getElementById('chatbot-input');
    const button = document.getElementById('chatbot-send');
    
    if (input) input.disabled = false;
    if (button) button.disabled = false;
}

function addMessage(content, isUser = false) {
    const messagesContainer = document.getElementById('chatbot-messages');
    if (!messagesContainer) return;
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
    
    messageDiv.innerHTML = `
        <i class="fas ${isUser ? 'fa-user' : 'fa-robot'}"></i>
        <div class="message-content">${content}</div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function showTypingIndicator() {
    const messagesContainer = document.getElementById('chatbot-messages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'message bot-message typing-indicator';
    typingDiv.id = 'typing-indicator';
    
    typingDiv.innerHTML = `
        <i class="fas fa-robot"></i>
        <div class="message-content">
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
    `;
    
    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function removeTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

async function sendMessage() {
    const input = document.getElementById('chatbot-input');
    const message = input.value.trim();
    
    if (!message) {
        return;
    }
    
    // Se não estiver conectado, tenta reconectar primeiro
    if (!isConnected) {
        addMessage('Tentando reconectar...', false);
        await initializeChatbot();
        
        // Se ainda não conseguiu conectar, mostra mensagem de erro
        if (!isConnected) {
            addMessage('Não foi possível conectar ao servidor. Verifique sua conexão com a internet.', false);
            return;
        }
    }
    
    // Adiciona mensagem do usuário
    addMessage(message, true);
    input.value = '';
    
    // Mostra indicador de digitação
    showTypingIndicator();
    
    try {
        const response = await fetch('<?= base_url() ?>chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                message: message,
                session_id: chatbotSessionId
            })
        });
        
        const data = await response.json();
        
        // Remove indicador de digitação
        removeTypingIndicator();
        
        if (data.success) {
            addMessage(data.response);
        } else {
            addMessage('Desculpe, ocorreu um erro ao processar sua mensagem. Tente novamente.');
            console.error('Erro do chatbot:', data.message);
        }
    } catch (error) {
        removeTypingIndicator();
        addMessage('Erro de conexão. Verifique sua internet e tente novamente.');
        console.error('Erro ao enviar mensagem:', error);
    }
}
</script>
