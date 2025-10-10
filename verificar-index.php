<?php
// Verificar conteúdo do index.php no servidor
echo "<h1>🔍 Verificação do index.php do Servidor</h1>";
echo "<hr>";

$arquivo = 'application/views/clientes/index.php';

if (file_exists($arquivo)) {
    echo "<h2>📄 Conteúdo Atual do Servidor:</h2>";
    echo "<strong>Arquivo:</strong> $arquivo<br>";
    echo "<strong>Tamanho:</strong> " . filesize($arquivo) . " bytes<br>";
    echo "<strong>Modificado:</strong> " . date('Y-m-d H:i:s', filemtime($arquivo)) . "<br><br>";
    
    $conteudo = file_get_contents($arquivo);
    
    // Verificar se tem Font Awesome
    $tem_font_awesome = strpos($conteudo, 'font-awesome') !== false;
    echo "<strong>Font Awesome:</strong> " . ($tem_font_awesome ? '✅ Incluído' : '❌ Não incluído') . "<br>";
    
    // Verificar se tem widget
    $tem_widget = strpos($conteudo, 'widget-simple') !== false;
    echo "<strong>Widget do Chatbot:</strong> " . ($tem_widget ? '✅ Incluído' : '❌ Não incluído') . "<br>";
    
    echo "<hr>";
    echo "<h3>📝 Últimas 20 linhas do arquivo:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
    
    $linhas = explode("\n", $conteudo);
    $total_linhas = count($linhas);
    $inicio = max(0, $total_linhas - 20);
    
    for ($i = $inicio; $i < $total_linhas; $i++) {
        $numero = $i + 1;
        echo sprintf("%3d: %s\n", $numero, htmlspecialchars($linhas[$i]));
    }
    
    echo "</pre>";
    
} else {
    echo "<div style='color: red;'>❌ Arquivo não encontrado: $arquivo</div>";
}

echo "<hr>";
echo "<h3>🔗 Links de Teste:</h3>";
echo "<a href='cliente' target='_blank'>🏠 Sistema de Clientes</a><br>";
echo "<a href='diagnostico.php' target='_blank'>🔍 Diagnóstico Completo</a><br>";
?>