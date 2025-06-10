<?php

declare(strict_types=1);

namespace SecretariaFiap\Helpers;

interface OutputObjectInterface
{
    /**
     * Converte o objeto de dados de saída para um array, tipicamente para serialização JSON.
     * @return array<string, mixed>
     */
    public function toArray(): array;

}
