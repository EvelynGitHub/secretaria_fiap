<?php

declare(strict_types=1);

namespace Tests;

use PDO;

class ConexaoTeste extends \SecretariaFiap\Infra\Banco\Conexao
{
    public static function obter(): PDO
    {
        if (php_sapi_name() === 'cli' && isset($GLOBALS['__pdo_test__'])) {
            return $GLOBALS['__pdo_test__'];
        }
    }

}
