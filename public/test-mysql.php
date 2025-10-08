<?php
$host = 'br908.hostgator.com.br';
$user = 'con23128_admin';
$pass = '10Kpormes!';
$db   = 'con23128_clientes';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('❌ Conexão falhou: ' . mysqli_connect_error());
}
echo '✅ Conexão MySQL estabelecida com sucesso!';
