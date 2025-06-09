<?php

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Estudante;

interface EstudanteRepositorio
{
    public function cadastrar(Estudante $estudante): Estudante;
    public function editar(Estudante $estudante): bool;
    public function deletar(string $uuid): bool;
    public function buscarPorUuid(string $uuid): Estudante;
    public function listarPorFiltros(string $nome, int $offset = 0, int $limit = 10): Estudante;
}
