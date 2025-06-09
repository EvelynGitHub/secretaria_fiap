<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\PDO;

use SecretariaFiap\Core\Contratos\Repositorio\EstudanteRepositorio;
use SecretariaFiap\Core\Entidade\Estudante;

class EstudanteRepositorioPDO implements EstudanteRepositorio
{
    public function buscarPorUuid(string $uuid): Estudante
    {
        // TODO: Implement buscarPorUuid() method.
        throw new \RuntimeException('Not implemented');
    }

    public function cadastrar(Estudante $estudante): Estudante
    {
        // TODO: Implement cadastrar() method.
        return $estudante;
    }

    public function deletar(string $uuid): bool
    {
        // TODO: Implement deletar() method.
        throw new \RuntimeException('Not implemented');
    }

    public function editar(Estudante $estudante): bool
    {
        // TODO: Implement editar() method.
        throw new \RuntimeException('Not implemented');
    }

    public function listarPorFiltros(string $nome, int $offset = 0, int $limit = 10): Estudante
    {
        // TODO: Implement listarPorFiltros() method.
        throw new \RuntimeException('Not implemented');
    }
}
