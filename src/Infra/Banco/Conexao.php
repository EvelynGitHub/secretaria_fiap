<?php

declare(strict_types=1);

namespace SecretariaFiap\Infra\Banco;

use PDO;
use PDOException;

class Conexao
{
    private static ?PDO $pdo = null;

    public static function obter(): PDO
    {
        if (self::$pdo === null) {
            $host = getenv('DB_HOST') ?: 'mysql';
            $dbname = getenv('DB_NAME') ?: 'example_fiap';
            $user = getenv('DB_USER_NAME') ?: 'fiap';
            $pass = getenv('DB_USER_PASSWD') ?: 'toor';
            $port = getenv('DB_PORT') ?: '3306';

            try {
                self::$pdo = new PDO(
                    "mysql:host={$host};dbname={$dbname};port={$port};charset=utf8",
                    $user,
                    $pass,
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_CASE => PDO::CASE_NATURAL
                    ]
                );

            } catch (PDOException $e) {
                die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
