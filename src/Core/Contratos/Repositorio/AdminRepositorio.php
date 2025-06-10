<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Admin;

interface AdminRepositorio
{
    public function buscarPorEmail(string $email): ?Admin;
}