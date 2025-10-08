<?php
namespace App\Infrastructure;

use App\Repositories\ClienteRepositoryInterface;
use App\Repositories\ClienteRepositoryMysql;

class RepositoryFactory
{
    public static function createClienteRepository(): ?ClienteRepositoryInterface
    {
        $pdo = DatabaseFactory::createFromCiConfig();
        if (!$pdo) return null;

        return new ClienteRepositoryMysql($pdo);
    }
}
