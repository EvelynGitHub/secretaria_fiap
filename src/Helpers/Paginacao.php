<?php

declare(strict_types=1);

namespace SecretariaFiap\Helpers;

/**
 * @template T
 */
class Paginacao
{
    /**
     * @param int $paginaAtual A página atual da paginação.
     * @param int $totalRegistros O número total de registros encontrados.
     * @param int $totalPaginas O número total de páginas.
     * @param int $limite O limite de registros por página.
     * @param T[] $itens Um array de itens do tipo T.
     */
    public function __construct(
        public int $paginaAtual,
        public int $totalRegistros,
        public int $totalPaginas,
        public int $limite,
        public array $itens
    ) {
    }
}