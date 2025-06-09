<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Matricula;

interface MatriculaRepositorio
{
    public function salvar(Matricula $matricula): void;
    public function existe(int $idAluno, int $idTurma): bool;
}
