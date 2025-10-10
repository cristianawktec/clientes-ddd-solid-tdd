<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS  
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
*/

$active_group = 'default';
$query_builder = TRUE;

// Configuração para servidor da Hostgator
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'br908.hostgator.com.br',
    'username' => 'con23128_admin',
    'password' => '10Kpormes!', // Esta linha pode estar causando problema
    'database' => 'con23128_clientes',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE, // deixe TRUE por enquanto - AQUI ESTÁ O PROBLEMA!
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

/*
| Algumas linhas adicionais para simular o arquivo do servidor
| Linha 30
| Linha 31  
| Linha 32
| Linha 33
| Linha 34
| Linha 35
| Linha 36
| Linha 37
| Linha 38
| Linha 39
| Linha 40
| Linha 41
| Linha 42
| Linha 43
| Linha 44
| Linha 45
| Linha 46
| Linha 47
| Linha 48
| Linha 49
| Linha 50
| Linha 51
| Linha 52
| Linha 53
| Linha 54
| Linha 55
| Linha 56
| Linha 57
| Linha 58
| Linha 59
| Linha 60
| Linha 61
| Linha 62
| Linha 63
| Linha 64
| Linha 65
| Linha 66
| Linha 67
| Linha 68
| Linha 69
| Linha 70
| Linha 71
| Linha 72
| Linha 73
| Linha 74
| Linha 75
| Linha 76
| Linha 77
| Linha 78
| Linha 79
| Linha 80
| Linha 81
| Linha 82
| Linha 83
| Linha 84
| Linha 85
| Linha 86
*/
// Linha 87 - aqui pode estar o problema: deixe TRUE por enquanto