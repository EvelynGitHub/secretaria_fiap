<?php

declare(strict_types=1);

namespace SecretariaFiap\Helpers;

use Ramsey\Uuid\Uuid;

class GeradorUuid
{
    public static function gerar(): string
    {
        return Uuid::uuid4()->toString();
    }
}
