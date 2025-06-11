<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SecretariaFiap\Adapter\Controller\AdminController;
use SecretariaFiap\Adapter\Controller\AlunoController;
use SecretariaFiap\Adapter\Controller\MatriculaController;
use SecretariaFiap\Adapter\Controller\TurmaController;
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
    $response->getBody()->write(json_encode(["Ola3", getenv('TOKEN_SALT')]));
    return $response;
});

$app->group('/api', function (RouteCollectorProxy $api) {
    $api->post('/admin/login', AdminController::class . ":login");
});

$app->group('/api', function (RouteCollectorProxy $api) {
    $api->post('/admin/logout', AdminController::class . ":logout");

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

})->add(Middleware::class);


$app->run();