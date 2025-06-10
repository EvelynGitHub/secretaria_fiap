<?php

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Helpers\Paginacao;

interface AlunoRepositorio
{
    public function cadastrar(Aluno $aluno): Aluno;
    public function editar(Aluno $aluno): bool;
    public function deletar(string $uuid): bool;
    public function buscarPorUuid(string $uuid): ?Aluno;
    public function buscarPorCpf(string $cpf): ?Aluno;
    /**
     * Lista alunos por filtros, retornando um objeto de paginação.
     *
     * @param string $nome O nome a ser filtrado.
     * @param int $offset O offset para a paginação.
     * @param int $limit O limite de registros por página.
     * @return Paginacao<Aluno> Um objeto Paginacao contendo uma lista de objetos Aluno.
     */
    public function listarPorFiltros(string $nome, int $offset = 0, int $limit = 10): Paginacao;

}
