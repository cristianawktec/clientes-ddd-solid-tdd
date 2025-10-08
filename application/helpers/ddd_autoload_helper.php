<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Autoloader simples para a estrutura DDD Light.
 * - Registra uma função que mapeia classes para arquivos dentro de application/
 * - Para ativar: adicione 'ddd_autoload' em $autoload['helper'] em application/config/autoload.php
 */

if (!function_exists('ddd_autoload_register')) {
    function ddd_autoload_register()
    {
        spl_autoload_register(function ($class) {
            $base = APPPATH; // application/
            $map = [
                // entities
                'ClienteEntity' => $base . 'domain/entities/ClienteEntity.php',
                'EnderecoEntity' => $base . 'domain/entities/EnderecoEntity.php',
                // repositories
                'ClienteRepository' => $base . 'infrastructure/repositories/ClienteRepository.php',
                // services infra
                'CepService' => $base . 'infrastructure/services/CepService.php',
                // domain services / usecases
                'ClienteService' => $base . 'domain/services/ClienteService.php',
                'CadastrarCliente' => $base . 'usecases/CadastrarCliente.php',
            ];

            if (isset($map[$class]) && file_exists($map[$class])) {
                require_once $map[$class];
            }
        });
    }
}

ddd_autoload_register();
