<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Matricula;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Helpers\Paginacao;

interface MatriculaRepositorio
{
    public function salvar(Matricula $matricula): Matricula;
    public function existe(int $idAluno, int $idTurma): bool;
}
