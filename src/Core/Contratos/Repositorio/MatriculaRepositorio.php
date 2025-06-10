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

    /**
     * Lista alunos por turma, retornando um objeto de paginação.
     *
     * @param string $uuidTurma A turma que deve ter seus alunos listados.
     * @param int $offset O offset para a paginação.
     * @param int $limit O limite de registros por página.
     * @return Paginacao<Aluno> Um objeto Paginacao contendo uma lista de objetos Aluno.
     */
    public function listarPorFiltros(string $uuidTurma, int $offset = 0, int $limit = 10): Paginacao;
}
