<?php

namespace SecretariaFiap\Helpers;

use JsonSerializable;

/**
 * @template T of OutputObjectInterface
 */
class OutputPaginacaoObject implements JsonSerializable
{
    public readonly int $paginaAtual;
    public readonly int $totalRegistros;
    public readonly int $totalPaginas;
    public readonly int $limite;
    /**
     * @var T[]
     */
    public readonly array $itens; // Estes são os itens mapeados para OutputDataInterface

    /**
     * @param Paginacao<mixed> $entidadePaginacao O objeto Paginacao vindo do repositório (com entidades de domínio).
     * @param callable(mixed): T $mapperCallable Um callable que recebe um item da entidade e retorna um OutputDataInterface.
     */
    private function __construct(Paginacao $entidadePaginacao, callable $mapperCallable)
    {
        $this->paginaAtual = $entidadePaginacao->paginaAtual;
        $this->totalRegistros = $entidadePaginacao->totalRegistros;
        $this->totalPaginas = $entidadePaginacao->totalPaginas;
        $this->limite = $entidadePaginacao->limite;

        // Mapeia os itens da paginação de entidade para os itens de OutputData
        $this->itens = array_map($mapperCallable, $entidadePaginacao->itens);
    }

    /**
     * Cria um OutputPaginacaoObject a partir de uma instância de Paginacao de entidades
     * e um callable para mapear cada item da entidade para o OutputData.
     *
     * @template K
     * @param Paginacao<K> $entidadePaginacao O objeto Paginacao vindo do repositório (com entidades de domínio).
     * @param callable(K): T $mapperCallable Um callable que recebe um item da entidade e retorna um OutputDataInterface.
     * @return OutputPaginacaoObject<T>
     */
    public static function create(Paginacao $entidadePaginacao, callable $mapperCallable): self
    {
        return new self($entidadePaginacao, $mapperCallable);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        // Converte os itens para array usando o método toArray() de cada OutputDataInterface
        $mappedItems = array_map(fn($item) => $item->toArray(), $this->itens);

        return [
            'pagina_atual' => $this->paginaAtual,
            'total_registros' => $this->totalRegistros,
            'total_paginas' => $this->totalPaginas,
            'limite' => $this->limite,
            'itens' => $mappedItems,
        ];
    }
}