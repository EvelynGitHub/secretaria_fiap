<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SecretariaFiap\Core\CasosUso\Admin\Login;
use SecretariaFiap\Core\CasosUso\Aluno\Atualizar;
use SecretariaFiap\Core\CasosUso\Aluno\Cadastrar;
use SecretariaFiap\Core\CasosUso\Aluno\InputObject;
use SecretariaFiap\Core\CasosUso\Aluno\Listar;
use SecretariaFiap\Core\CasosUso\Aluno\ListarPorTurma;
use SecretariaFiap\Core\CasosUso\Aluno\Obter;
use SecretariaFiap\Core\CasosUso\Aluno\Remover;
use SecretariaFiap\Core\Contratos\Repositorio\AdminRepositorio;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Contratos\Repositorio\MatriculaRepositorio;
use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;

// Inicio: Injeção de dependência 
require_once __DIR__ . "/../config/dependencyInjection.php"; // $container

$repositorio = $container->get(AlunoRepositorio::class);
$repositorioTurma = $container->get(TurmaRepositorio::class);
$repositorioMatricula = $container->get(MatriculaRepositorio::class);
$repositorioAdmin = $container->get(AdminRepositorio::class);

// Fim: Injeção de dependência 

// Inicio: Testes dos CRUDs
// --> Aluno
// $casoUsoCadastro = new Cadastrar($repositorio);

// $input = InputObject::create([
//     'nome' => 'João Silva DI',
//     'cpf' => '22222222222',
//     'email' => 'joao@exemplo.com.br',
//     'senha' => 'SenhaSegura123!',
//     'data_nascimento' => '1998-01-01'
// ]);

// $output = $casoUsoCadastro->executar($input);

// render($output, "Cadastro de João");


// $casoUsoObter = new Obter($repositorio);
// $outputObter = $casoUsoObter->executar($output->uuid);
// render($outputObter, "Obtendo dados do João");


// $edit = InputObject::create([
//     'nome' => $outputObter->nome . " Editado",
//     'cpf' => $outputObter->cpf,
//     'email' => $outputObter->email,
//     // 'senha' => "Abc123@@",
//     'data_nascimento' => $output->dataNascimento,
//     'uuid' => $outputObter->uuid
// ]);
// $casoUsoEditar = new Atualizar($repositorio);
// $outputObter = $casoUsoEditar->executar($edit);
// render($outputObter, "Atualizando dados do João");


// $casoUsoListar = new Listar($repositorio);
// $outputLista = $casoUsoListar->executar();
// render($outputLista, "Listando dados de aluno");


// $casoUsoRemover = new Remover($repositorio);
// $outputRemove = $casoUsoRemover->executar($output->uuid);
// render($outputRemove, "Removendo dados do João");

// 3e4d1dca-6d7c-4869-91af-411c21350b40 - Turma sem alunos
// e2f52eb1-8fca-4de1-9d55-03eb24267f7f - Turma com alunos
// $casoUsoListarAlunosPorTurma = new ListarPorTurma($repositorio);
// $outputRemove = $casoUsoListarAlunosPorTurma->executar('e2f52eb1-8fca-4de1-9d55-03eb24267f7f');
// render($outputRemove, "Alunos por Turma");



// --> Turma
// $casoUsoCadastroT = new \SecretariaFiap\Core\CasosUso\Turma\Cadastrar($repositorioTurma);

// $input = \SecretariaFiap\Core\CasosUso\Turma\InputObject::create([
//     'nome' => 'Turma de IA 1',
//     'descricao' => "Engenharia de IA"
// ]);

// $output = $casoUsoCadastroT->executar($input);

// render($output, "Cadastro de Turma IA");

// $casoUsoObterT = new \SecretariaFiap\Core\CasosUso\Turma\Obter($repositorioTurma);
// $outputObterT = $casoUsoObterT->executar($output->uuid);
// render($outputObterT, "Obtendo dados do Turma IA");


// $editT = \SecretariaFiap\Core\CasosUso\Turma\InputObject::create([
//     'nome' => $outputObterT->nome . " Abacaxi",
//     // 'descricao' => "Engenharia de IA",
//     'uuid' => $outputObterT->uuid
// ]);
// $casoUsoEditar = new \SecretariaFiap\Core\CasosUso\Turma\Atualizar($repositorioTurma);
// $outputEditT = $casoUsoEditar->executar($editT);
// render($outputEditT, "Atualizando dados do Turma IA");


// $casoUsoListarT = new \SecretariaFiap\Core\CasosUso\Turma\Listar($repositorioTurma);
// $outputListaT = $casoUsoListarT->executar();
// render($outputListaT, "Listando dados das turmas");


// $casoUsoRemoverT = new \SecretariaFiap\Core\CasosUso\Turma\Remover($repositorioTurma);
// $outputRemoveT = $casoUsoRemoverT->executar($output->uuid);
// render($outputRemoveT, "Removendo dados do Turma IA");


// --> Matricula
// $input = \SecretariaFiap\Core\CasosUso\Matricula\InputObject::create([
//     'uuid_aluno' => '52f7b495-458d-11f0-a0d1-4208e9f4cc4d',
//     'uuid_turma' => 'e2f52eb1-8fca-4de1-9d55-03eb24267f7f'
// ]);

// $casoUsoMatricula = new \SecretariaFiap\Core\CasosUso\Matricula\MatricularAluno(
//     $repositorio,
//     $repositorioTurma,
//     $repositorioMatricula
// );

// $output = $casoUsoMatricula->executar($input);

// render($output, "Matriculando Aluno");

// --> Admin
use SecretariaFiap\Core\CasosUso\Admin\InputObject as AdminInput;

$login = new Login($repositorioAdmin);
$input = AdminInput::create([
    'email' => 'admin@fiap.com.br',
    'senha' => 'Admin@123'
]);
$output = $login->executar($input);
render($output, "Login do Admin");

// Inicio: Testes dos CRUDs

function render($dados, $mensagem)
{
    if (!empty($mensagem))
        echo "<h3> -------- $mensagem -------- </h3>";
    echo "<pre>";
    print_r($dados);
    echo "</pre>";
    echo "<hr>";
}