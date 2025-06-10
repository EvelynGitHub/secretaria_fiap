<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SecretariaFiap\Core\CasosUso\Aluno\Atualizar;
use SecretariaFiap\Core\CasosUso\Aluno\Cadastrar;
use SecretariaFiap\Core\CasosUso\Aluno\InputObject;
use SecretariaFiap\Core\CasosUso\Aluno\Listar;
use SecretariaFiap\Core\CasosUso\Aluno\Obter;
use SecretariaFiap\Core\CasosUso\Aluno\Remover;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;

// Inicio: Injeção de dependência 
require_once __DIR__ . "/../config/dependencyInjection.php"; // $container

$repositorio = $container->get(AlunoRepositorio::class);

$casoUsoCadastro = new Cadastrar($repositorio);

$input = InputObject::create([
    'nome' => 'João Silva DI',
    'cpf' => '22222222222',
    'email' => 'joao@exemplo.com.br',
    'senha' => 'SenhaSegura123!',
    'data_nascimento' => '1998-01-01'
]);

$output = $casoUsoCadastro->executar($input);

render($output, "Cadastro de João");


$casoUsoObter = new Obter($repositorio);
$outputObter = $casoUsoObter->executar($output->uuid);
render($outputObter, "Obtendo dados do João");


$edit = InputObject::create([
    'nome' => $outputObter->nome . " Editado",
    'cpf' => $outputObter->cpf,
    'email' => $outputObter->email,
    // 'senha' => "Abc123@@",
    'data_nascimento' => $output->dataNascimento,
    'uuid' => $outputObter->uuid
]);
$casoUsoEditar = new Atualizar($repositorio);
$outputObter = $casoUsoEditar->executar($edit);
render($outputObter, "Atualizando dados do João");


$casoUsoListar = new Listar($repositorio);
$outputLista = $casoUsoListar->executar();
render($outputLista, "Listando dados de aluno");


$casoUsoRemover = new Remover($repositorio);
$outputRemove = $casoUsoRemover->executar($output->uuid);
render($outputRemove, "Removendo dados do João");
// Fim: Injeção de dependência 


function render($dados, $mensagem)
{
    if (!empty($mensagem))
        echo "<h3> -------- $mensagem -------- </h3>";
    echo "<pre>";
    print_r($dados);
    echo "</pre>";
    echo "<hr>";
}