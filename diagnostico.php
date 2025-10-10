<?php
// Script de diagnóstico para executar diretamente no servidor
// Salve como: diagnostico.php na raiz do projeto

echo "<h1>🔍 Diagnóstico do Servidor - Hostgator</h1>";
echo "<hr>";

// Informações do servidor
echo "<h2>📊 Informações do Servidor</h2>";
echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
echo "<strong>Server:</strong> " . $_SERVER['SERVER_NAME'] . "<br>";
echo "<strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "<strong>Current Directory:</strong> " . getcwd() . "<br>";
echo "<strong>Date/Time:</strong> " . date('Y-m-d H:i:s') . "<br>";
echo "<hr>";

// Verificar arquivos críticos
echo "<h2>📁 Verificação de Arquivos</h2>";

$arquivos_criticos = [
    'application/config/database.php' => 'Configuração do banco',
    'application/views/clientes/index.php' => 'View principal',
    'application/views/chatbot/widget-simple.php' => 'Widget do chatbot',
    'application/controllers/Chatbot.php' => 'Controlador do chatbot',
    'application/controllers/Cliente.php' => 'Controlador principal'
];

foreach ($arquivos_criticos as $arquivo => $descricao) {
    $existe = file_exists($arquivo);
    $cor = $existe ? 'green' : 'red';
    $status = $existe ? '✅ Existe' : '❌ Não existe';
    
    echo "<div style='color: $cor; margin: 5px 0;'>";
    echo "<strong>$descricao:</strong> $status - $arquivo";
    
    if ($existe) {
        $tamanho = filesize($arquivo);
        $modificado = date('Y-m-d H:i:s', filemtime($arquivo));
        echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;Tamanho: " . number_format($tamanho) . " bytes | Modificado: $modificado";
        
        // Verificações específicas
        if ($arquivo === 'application/config/database.php') {
            $conteudo = file_get_contents($arquivo);
            $tem_hostgator = strpos($conteudo, 'br908.hostgator.com.br') !== false;
            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;Config Hostgator: " . ($tem_hostgator ? '✅ Configurado' : '❌ Não configurado');
        }
        
        if ($arquivo === 'application/views/clientes/index.php') {
            $conteudo = file_get_contents($arquivo);
            $tem_font_awesome = strpos($conteudo, 'font-awesome') !== false;
            $tem_widget = strpos($conteudo, 'widget-simple') !== false;
            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;Font Awesome: " . ($tem_font_awesome ? '✅' : '❌');
            echo " | Widget: " . ($tem_widget ? '✅' : '❌');
        }
        
        if ($arquivo === 'application/controllers/Chatbot.php') {
            $conteudo = file_get_contents($arquivo);
            $tem_api_key = strpos($conteudo, 'AIzaSyCmgb11bxDwuT4B2trEm8v_hVfdXHiXYFI') !== false;
            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;API Key: " . ($tem_api_key ? '✅ Configurada' : '❌ Não configurada');
        }
    }
    echo "</div>";
}

echo "<hr>";

// Verificar permissões
echo "<h2>🔐 Verificação de Permissões</h2>";
foreach ($arquivos_criticos as $arquivo => $descricao) {
    if (file_exists($arquivo)) {
        $perms = substr(sprintf('%o', fileperms($arquivo)), -4);
        $legivel = is_readable($arquivo) ? '✅' : '❌';
        $executavel = is_executable($arquivo) ? '✅' : '❌';
        
        echo "<div style='margin: 5px 0;'>";
        echo "<strong>$descricao:</strong> Permissões: $perms | Legível: $legivel | Executável: $executavel";
        echo "</div>";
    }
}

echo "<hr>";

// Testar CodeIgniter
echo "<h2>🚀 Teste CodeIgniter</h2>";
if (file_exists('index.php')) {
    echo "✅ index.php existe<br>";
    
    // Tentar carregar configuração básica
    if (defined('BASEPATH')) {
        echo "✅ CodeIgniter carregado<br>";
    } else {
        echo "⚠️ CodeIgniter não carregado neste contexto<br>";
    }
} else {
    echo "❌ index.php não encontrado<br>";
}

echo "<hr>";

// Informações de conexão com banco
echo "<h2>🗄️ Teste de Conexão com Banco</h2>";
if (file_exists('application/config/database.php')) {
    include_once 'application/config/database.php';
    
    if (isset($db['default'])) {
        $config = $db['default'];
        echo "<strong>Host:</strong> " . $config['hostname'] . "<br>";
        echo "<strong>Database:</strong> " . $config['database'] . "<br>";
        echo "<strong>Username:</strong> " . $config['username'] . "<br>";
        
        // Tentar conexão (cuidado - não expor senha)
        try {
            $conexao = new mysqli(
                $config['hostname'],
                $config['username'],
                $config['password'],
                $config['database']
            );
            
            if ($conexao->connect_error) {
                echo "<div style='color: red;'>❌ Erro de conexão: " . $conexao->connect_error . "</div>";
            } else {
                echo "<div style='color: green;'>✅ Conexão com banco bem-sucedida!</div>";
                $conexao->close();
            }
        } catch (Exception $e) {
            echo "<div style='color: red;'>❌ Erro ao conectar: " . $e->getMessage() . "</div>";
        }
    }
} else {
    echo "❌ Arquivo de configuração não encontrado";
}

echo "<hr>";

// URLs de teste
echo "<h2>🔗 URLs de Teste</h2>";
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
if ($base_url[strlen($base_url)-1] !== '/') $base_url .= '/';

echo "<a href='{$base_url}' target='_blank'>🏠 Página Principal</a><br>";
echo "<a href='{$base_url}cliente' target='_blank'>👥 Sistema de Clientes</a><br>";
echo "<a href='{$base_url}chatbot/test' target='_blank'>🤖 Teste API Chatbot</a><br>";
echo "<a href='{$base_url}verificar-atualizacao' target='_blank'>✅ Verificar Atualizações</a><br>";

echo "<hr>";
echo "<p><em>Diagnóstico executado em: " . date('Y-m-d H:i:s') . "</em></p>";
?>