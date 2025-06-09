<?php

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Aluno;

interface AlunoRepositorio
{
    public function cadastrar(Aluno $aluno): Aluno;
    public function editar(Aluno $aluno): bool;
    public function deletar(string $uuid): bool;
    public function buscarPorUuid(string $uuid): ?Aluno;
    public function buscarPorCpf(string $cpf): ?Aluno;
    /**
     * Retorna uma lista paginada de alunos que pode ser filtrada pelo nome
     * @param string $nome Nome do aluno
     * @param int $offset
     * @param int $limit
     * @return object {} contendo os metadados da paginação e a lista de Aluno
     */
    public function listarPorFiltros(string $nome, int $offset = 0, int $limit = 10): object;

}
