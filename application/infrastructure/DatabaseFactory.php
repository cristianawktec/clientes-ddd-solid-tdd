<?php
namespace App\Infrastructure;

class DatabaseFactory
{
    public static function createFromCiConfig(): ?\PDO
    {
        $cfgFile = APPPATH . 'config/database.php';
        if (!file_exists($cfgFile)) {
            return null;
        }

        // include the CI database config which defines $db and $active_group
        include $cfgFile;
        $group = $active_group ?? 'default';
        $cfg = $db[$group] ?? null;
        if (!$cfg) return null;

        $driver = $cfg['dbdriver'] ?? 'mysqli';
        $host = $cfg['hostname'] ?? 'localhost';
        $name = $cfg['database'] ?? '';
        $user = $cfg['username'] ?? '';
        $pass = $cfg['password'] ?? '';
        $charset = $cfg['char_set'] ?? 'utf8';

        if ($driver === 'mysqli' || $driver === 'mysql') {
            $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";
        } else {
            // unsupported driver for now
            return null;
        }

        try {
            return new \PDO($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        } catch (\PDOException $e) {
            log_message('error', 'DB Connection error: ' . $e->getMessage());
            return null;
        }
    }
}
