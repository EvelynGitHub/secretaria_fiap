<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Turma;
use SecretariaFiap\Helpers\Paginacao;

interface TurmaRepositorio
{
    public function buscarPorUuid(string $uuid): ?Turma;

    public function cadastrar(Turma $turma): Turma;
    public function editar(Turma $turma): bool;
    public function deletar(string $uuid): bool;

    /**
     * Lista turmas por filtros, retornando um objeto de paginação.
     *
     * @param string $nome O nome da turma a ser filtrado.
     * @param int $offset O offset para a paginação.
     * @param int $limit O limite de registros por página.
     * @return Paginacao<Turma> Um objeto Paginacao contendo uma lista de objetos Turma.
     */
    public function listarPorFiltros(string $nome, int $offset = 0, int $limit = 10): Paginacao;
}
