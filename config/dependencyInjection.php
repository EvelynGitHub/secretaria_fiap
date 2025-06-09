<?php

use SecretariaFiap\Adapter\PDO\AlunoRepositorioPDO;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;

// DependencyInjection - Injeção de dependências 


$containerBuilder = new \DI\ContainerBuilder();

$containerBuilder->addDefinitions([
    AlunoRepositorio::class => \DI\get(AlunoRepositorioPDO::class),
    // AlunoRepositorio::class => \DI\get(AlunoRepositorioPDO::class),
]);

$container = $containerBuilder->build();