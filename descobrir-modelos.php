<?php
// Descobrir quais modelos Gemini estão disponíveis
$api_key = 'AIzaSyCmgb11bxDwuT4B2trEm8v_hVfdXHiXYFI';

echo "<h1>🔍 Descobrindo Modelos Gemini Disponíveis</h1>";
echo "<hr>";

// Listar modelos disponíveis
$list_url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $api_key;

echo "<h3>📋 Consultando modelos disponíveis...</h3>";
echo "<strong>URL:</strong> $list_url<br><br>";

$ch = curl_init($list_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<strong>Status HTTP:</strong> $http_code<br>";

if ($http_code === 200) {
    $decoded_response = json_decode($response, true);
    
    if ($decoded_response && isset($decoded_response['models'])) {
        echo "<h3>✅ Modelos Disponíveis:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f5f5f5;'><th>Nome</th><th>Suporta generateContent</th><th>Usar para teste</th></tr>";
        
        $modelos_validos = [];
        
        foreach ($decoded_response['models'] as $model) {
            $nome = $model['name'];
            $suporta_generate = in_array('generateContent', $model['supportedGenerationMethods'] ?? []);
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($nome) . "</td>";
            echo "<td>" . ($suporta_generate ? '✅ Sim' : '❌ Não') . "</td>";
            
            if ($suporta_generate) {
                $modelos_validos[] = $nome;
                echo "<td>✅ Válido</td>";
            } else {
                echo "<td>❌ Não usar</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        if (!empty($modelos_validos)) {
            $primeiro_modelo = $modelos_validos[0];
            echo "<hr>";
            echo "<h3>🧪 Testando o primeiro modelo válido: <code>$primeiro_modelo</code></h3>";
            
            // Testar o primeiro modelo válido
            $test_url = "https://generativelanguage.googleapis.com/v1beta/$primeiro_modelo:generateContent?key=" . $api_key;
            
            $prompt = "Olá! Você é um assistente virtual do sistema de clientes. Como você pode ajudar?";
            
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
            
            echo "<strong>URL de teste:</strong> $test_url<br>";
            echo "<strong>Prompt:</strong> $prompt<br><br>";
            
            $ch = curl_init($test_url);
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
            
            $test_response = curl_exec($ch);
            $test_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "<strong>Status do teste:</strong> $test_http_code<br>";
            
            if ($test_http_code === 200) {
                $test_decoded = json_decode($test_response, true);
                
                if ($test_decoded && isset($test_decoded['candidates'][0]['content']['parts'][0]['text'])) {
                    $ai_response = trim($test_decoded['candidates'][0]['content']['parts'][0]['text']);
                    
                    echo "<h3>🎉 SUCESSO! Resposta da IA:</h3>";
                    echo "<div style='background: #e8f5e8; padding: 15px; border: 1px solid #4CAF50; border-radius: 5px;'>";
                    echo nl2br(htmlspecialchars($ai_response));
                    echo "</div>";
                    
                    echo "<hr>";
                    echo "<h3>🔧 Modelo para usar no chatbot:</h3>";
                    echo "<code style='background: #f5f5f5; padding: 10px; display: block;'>";
                    echo htmlspecialchars($primeiro_modelo);
                    echo "</code>";
                    
                } else {
                    echo "<h3>❌ Resposta inválida:</h3>";
                    echo "<pre>" . htmlspecialchars($test_response) . "</pre>";
                }
            } else {
                echo "<h3>❌ Erro no teste:</h3>";
                echo "<pre>" . htmlspecialchars($test_response) . "</pre>";
            }
        }
        
    } else {
        echo "<h3>❌ Resposta inválida da API de listagem</h3>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
    }
} else {
    echo "<h3>❌ Erro HTTP: $http_code</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

echo "<hr>";
echo "<p><strong>🎯 Objetivo:</strong> Encontrar o modelo correto que funciona e atualizar o chatbot!</p>";
?>