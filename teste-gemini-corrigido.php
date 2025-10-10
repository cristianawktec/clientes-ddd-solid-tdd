<?php
// Teste com o modelo correto do Gemini
$api_key = 'AIzaSyCmgb11bxDwuT4B2trEm8v_hVfdXHiXYFI';
$user_message = 'Olá, como funciona o sistema de clientes?';

echo "<h1>🧪 Teste Gemini - Modelo Correto</h1>";
echo "<hr>";

// Contexto otimizado
$prompt = "Você é um assistente virtual do sistema de gerenciamento de clientes.

Sistema: PHP CodeIgniter 3 com MySQL
Funcionalidades: cadastro, edição, exclusão de clientes, upload de fotos, busca por CEP

Pergunta do usuário: $user_message

Responda de forma clara e objetiva em português:";

// USANDO O MODELO CORRETO: gemini-1.5-flash
$data = [
    'contents' => [
        [
            'parts' => [
                [
                    'text' => $prompt
                ]
            ]
        ]
    ]
];

// URL CORRIGIDA com modelo gemini-1.5-flash
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key;

echo "<strong>🔗 URL:</strong> " . $url . "<br>";
echo "<strong>📝 Modelo:</strong> gemini-1.5-flash<br>";
echo "<hr>";

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

echo "<h3>🚀 Testando API...</h3>";
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

echo "<strong>Status HTTP:</strong> $http_code<br>";

if ($curl_error) {
    echo "<div style='color: red;'><strong>Erro cURL:</strong> $curl_error</div>";
}

if ($http_code === 200) {
    $decoded_response = json_decode($response, true);
    
    if ($decoded_response && isset($decoded_response['candidates'][0]['content']['parts'][0]['text'])) {
        $ai_response = trim($decoded_response['candidates'][0]['content']['parts'][0]['text']);
        
        echo "<h3>✅ SUCESSO! Resposta da IA:</h3>";
        echo "<div style='background: #e8f5e8; padding: 15px; border: 1px solid #4CAF50; border-radius: 5px;'>";
        echo nl2br(htmlspecialchars($ai_response));
        echo "</div>";
        
        echo "<h3>🔧 Próximo passo:</h3>";
        echo "<p>✅ API funcionando! Agora vamos atualizar o controlador do chatbot para usar o modelo correto.</p>";
        
    } else {
        echo "<h3>❌ Estrutura de resposta inválida:</h3>";
        echo "<pre style='background: #f5f5f5; padding: 10px;'>";
        echo htmlspecialchars(json_encode($decoded_response, JSON_PRETTY_PRINT));
        echo "</pre>";
    }
} else {
    echo "<h3>❌ Erro HTTP: $http_code</h3>";
    echo "<pre style='background: #ffebee; padding: 10px;'>";
    echo htmlspecialchars($response);
    echo "</pre>";
}
?>