<?php

use SecretariaFiap\Adapter\PDO\AlunoRepositorioPDO;
use SecretariaFiap\Adapter\PDO\MatriculaRepositorioPDO;
use SecretariaFiap\Adapter\PDO\TurmaRepositorioPDO;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Contratos\Repositorio\MatriculaRepositorio;
use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;

// DependencyInjection - Injeção de dependências 


$containerBuilder = new \DI\ContainerBuilder();

$containerBuilder->addDefinitions([
    AlunoRepositorio::class => \DI\get(AlunoRepositorioPDO::class),
    TurmaRepositorio::class => \DI\get(TurmaRepositorioPDO::class),
    MatriculaRepositorio::class => \DI\get(MatriculaRepositorioPDO::class),
]);

$container = $containerBuilder->build();