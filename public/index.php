<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SecretariaFiap\Adapter\Controller\AlunoController;
use SecretariaFiap\Adapter\Controller\MatriculaController;
use SecretariaFiap\Adapter\Controller\TurmaController;
use SecretariaFiap\Core\CasosUso\Admin\Login;
use SecretariaFiap\Infra\Http\Middleware;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

session_start();

header("Accept: application/json; charset=UTF-8"); //Aceita apenas coisas enviadas como JSON
header("Content-Type: application/json; charset=UTF-8"); //tipo de retorno

// Inicio: Injeção de dependência 
require_once __DIR__ . "/../config/dependencyInjection.php"; // $container

// $container->set('view', fn() => new PhpRenderer(__DIR__ . '/../views'));

AppFactory::setContainer($container);
// Fim: Injeção de dependência 

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello World');
    return $response;
});

$app->get('/admin', function (Request $request, Response $response) {
    $caso = $this->get(Login::class);

    $input = SecretariaFiap\Core\CasosUso\Admin\InputObject::create([
        'email' => 'admin@fiap.com.br',
        'senha' => 'Admin@123'
    ]);

    $output = $caso->executar($input);

    $_SESSION['jwt'] = $output->token;

    $response->getBody()->write(json_encode([$output, $_SESSION]));
    return $response;
});


$app->group('/api', function (RouteCollectorProxy $api) {
    $api->get('/alunos', AlunoController::class . ":listar");
    $api->post('/alunos', AlunoController::class . ":cadastrar");
    $api->get('/alunos/{uuid}', AlunoController::class . ":obter");
    $api->put('/alunos/{uuid}', AlunoController::class . ":atualizar");
    $api->delete('/alunos/{uuid}', AlunoController::class . ":remover");
    $api->get('/alunos/turma/{uuid_turma}', AlunoController::class . ":listarPorTurma");

    $api->post('/turmas', TurmaController::class . ":cadastrar");
    $api->get('/turmas', TurmaController::class . ":listar");
    $api->get('/turmas/{uuid}', TurmaController::class . ":obter");
    $api->put('/turmas/{uuid}', TurmaController::class . ":atualizar");
    $api->delete('/turmas/{uuid}', TurmaController::class . ":remover");

    $api->post('/matriculas', MatriculaController::class . ":matricularAluno");

});//->add(Middleware::class);


$app->group('/api', function (RouteCollectorProxy $api) {
    $api->post('/admin/login', AlunoController::class . ":loginAdmin");
});

$app->run();



// Inicio: Testes dos CRUDs
// --> Aluno

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
// use SecretariaFiap\Core\CasosUso\Admin\InputObject as AdminInput;

// $login = new Login($repositorioAdmin);
// $input = AdminInput::create([
//     'email' => 'admin@fiap.com.br',
//     'senha' => 'Admin@123'
// ]);
// $output = $login->executar($input);
// render($output, "Login do Admin");

// // Inicio: Testes dos CRUDs

// function render($dados, $mensagem)
// {
//     if (!empty($mensagem))
//         echo "<h3> -------- $mensagem -------- </h3>";
//     echo "<pre>";
//     print_r($dados);
//     echo "</pre>";
//     echo "<hr>";
// }