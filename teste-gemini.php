<?php
// Teste direto da API Google Gemini
// Salve como: teste-gemini.php

$api_key = 'AIzaSyCmgb11bxDwuT4B2trEm8v_hVfdXHiXYFI';
$user_message = 'Olá, como funciona o sistema de clientes?';

echo "<h1>🧪 Teste da API Google Gemini</h1>";
echo "<hr>";

echo "<strong>API Key:</strong> " . substr($api_key, 0, 10) . "..." . substr($api_key, -10) . "<br>";
echo "<strong>Mensagem de teste:</strong> $user_message<br>";
echo "<hr>";

// Contexto melhorado
$context = "Você é um assistente virtual especializado no sistema de gerenciamento de clientes.

SOBRE O SISTEMA:
- Sistema desenvolvido em PHP com CodeIgniter 3
- Gerencia cadastro completo de clientes
- Funcionalidades: cadastro, edição, exclusão, upload de fotos
- Integração com ViaCEP para endereços
- Interface moderna com Bootstrap 5

INSTRUÇÕES:
- Responda sempre em português brasileiro
- Seja claro e objetivo
- Foque nas funcionalidades do sistema
- Mantenha tom profissional mas amigável
- Limite respostas a 2-3 parágrafos

Usuário pergunta: $user_message

Responda:";

$data = [
    'contents' => [
        [
            'parts' => [
                [
                    'text' => $context
                ]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 300,
    ],
    'safetySettings' => [
        [
            'category' => 'HARM_CATEGORY_HARASSMENT',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
            'category' => 'HARM_CATEGORY_HATE_SPEECH', 
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ]
    ]
];

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $api_key;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => true,
]);

echo "<h3>🚀 Fazendo chamada para API...</h3>";
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

echo "<strong>Status HTTP:</strong> $http_code<br>";

if ($curl_error) {
    echo "<div style='color: red;'><strong>Erro cURL:</strong> $curl_error</div>";
}

echo "<h3>📝 Resposta bruta da API:</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;'>";
echo htmlspecialchars($response);
echo "</pre>";

if ($http_code === 200) {
    $decoded_response = json_decode($response, true);
    
    if ($decoded_response && isset($decoded_response['candidates'][0]['content']['parts'][0]['text'])) {
        $ai_response = trim($decoded_response['candidates'][0]['content']['parts'][0]['text']);
        
        echo "<h3>✅ Resposta da IA:</h3>";
        echo "<div style='background: #e8f5e8; padding: 15px; border: 1px solid #4CAF50; border-radius: 5px;'>";
        echo nl2br(htmlspecialchars($ai_response));
        echo "</div>";
    } else {
        echo "<div style='color: red;'><strong>❌ Erro:</strong> Estrutura de resposta inválida</div>";
        
        if (isset($decoded_response['error'])) {
            echo "<div style='color: red;'><strong>Erro da API:</strong> " . $decoded_response['error']['message'] . "</div>";
        }
    }
} else {
    echo "<div style='color: red;'><strong>❌ Erro HTTP:</strong> $http_code</div>";
}

echo "<hr>";
echo "<h3>🔗 Links para testar:</h3>";
echo "<a href='cliente' target='_blank'>🏠 Sistema de Clientes</a><br>";
echo "<a href='chatbot/test' target='_blank'>🤖 Teste API Chatbot</a><br>";
echo "<a href='diagnostico.php' target='_blank'>🔍 Diagnóstico Completo</a><br>";
?>