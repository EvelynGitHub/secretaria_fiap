<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SecretariaFiap\Core\CasosUso\Aluno\Cadastrar;
use SecretariaFiap\Core\CasosUso\Aluno\InputObject;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;

// Inicio: Injeção de dependência 
require_once __DIR__ . "/../config/dependencyInjection.php"; // $container

/** @var AlunoRepositorio $repositorio */
// $repositorio = $container->get(AlunoRepositorio::class);

// $casoUsoCadastro = new Cadastrar($repositorio);

// $input = InputObject::create([
//     'nome' => 'João Silva DI',
//     'cpf' => '22222222222',
//     'email' => 'joao@exemplo.com.br',
//     'senha' => 'SenhaSegura123!',
//     'data_nascimento' => '1998-01-01'
// ]);

// $output = $casoUsoCadastro->executar($input);

// echo "<pre>";
// print_r($output);

// Fim: Injeção de dependência 

// Inicio: Documentação swagger
// $openapi = \OpenApi\Generator::scan([__DIR__ . '/../src/Infra/Http']);
// header('Content-Type: application/x-yaml');
// header("Accept: application/json; charset=UTF-8"); //Aceita apenas coisas enviadas como JSON
// // echo $openapi->toYaml();
// echo $openapi->toJson();

// $openapi = \OpenApi\Generator::scan([__DIR__ . '/../src/Infra/Http']);
// header("Content-Type: application/json; charset=UTF-8");
// echo $openapi->toJson();

// Fim: Documentação swagger
