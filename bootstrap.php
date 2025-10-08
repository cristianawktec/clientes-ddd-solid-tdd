<?php
/**
 * Bootstrap para PHPUnit
 * 
 * Responsável por carregar apenas o autoload do Composer,
 * sem passar pelo CodeIgniter para evitar o erro
 * "No direct script access allowed".
 */

// Define a constante BASEPATH para que arquivos do CodeIgniter
// que verificam `defined('BASEPATH')` não façam exit durante os testes.
// O valor pode ser qualquer coisa; usamos o diretório do projeto.
if (!defined('BASEPATH')) {
	define('BASEPATH', __DIR__);
}

// Define APPPATH caso algum arquivo a utilize durante os testes.
if (!defined('APPPATH')) {
	define('APPPATH', __DIR__ . '/application/');
}

require __DIR__ . '/vendor/autoload.php';

// Aqui você pode adicionar configs extras de teste, se precisar
// Exemplo: inicializar variáveis de ambiente, mocks, etc.
