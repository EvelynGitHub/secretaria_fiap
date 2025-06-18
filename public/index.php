<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SecretariaFiap\Adapter\Controller\AdminController;
use SecretariaFiap\Adapter\Controller\AlunoController;
use SecretariaFiap\Adapter\Controller\MatriculaController;
use SecretariaFiap\Adapter\Controller\TurmaController;
use SecretariaFiap\Helpers\View;
use SecretariaFiap\Infra\Http\Middleware;
use SecretariaFiap\Infra\Http\MiddlewareView;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Headers Http 
require_once __DIR__ . "/../config/headersHttp.php";
// Inicio: Injeção de dependência 
require_once __DIR__ . "/../config/dependencyInjection.php"; // $container
AppFactory::setContainer($container);
// Fim: Injeção de dependência 

// Inicio: Definição das rotas
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// ----------------------------------------------------------------------
// Endpoints (FRONTEND) - Estes devem renderizam as views
// ----------------------------------------------------------------------

$app->get('/', function (Request $request, Response $response) {
    return $response->withHeader('Location', '/login')->withStatus(302);
});

$app->get('/login', function (Request $request, Response $response, array $args) {
    return View::renderizar($response, __DIR__ . '/views/login.php', __DIR__ . '/views/layout_login.php');
});

// Grupo de rotas VIEW protegidas
$app->group('', function (RouteCollectorProxy $group) {
    $group->get('/dashboard', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/dashboard.php');
    });

    // Alunos
    $group->get('/alunos', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/alunos/listagem.php');
    });

    $group->get('/alunos/cadastro', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/alunos/cadastro.php', __DIR__ . '/views/layout.php');
    });

    $group->get('/alunos/edicao/{uuid}', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/alunos/edicao.php', null, ['uuid' => $args['uuid']]);
    });

    // Turmas
    $group->get('/turmas', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/turmas/listagem.php');
    });

    $group->get('/turmas/cadastro', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/turmas/cadastro.php', __DIR__ . '/views/layout.php');
    });

    $group->get('/turmas/edicao/{uuid}', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/turmas/edicao.php', null, ['uuid' => $args['uuid'] ?? null]);
    });

    // Matricula
    $group->get('/matriculas', function (Request $request, Response $response, array $args) {
        return View::renderizar($response, __DIR__ . '/views/matriculas/cadastro.php');
    });

})->add(Middleware::class)->add(MiddlewareView::class);


// ----------------------------------------------------------------------
// Endpoints da API (BACKEND) - Estes devem retornar JSON
// ----------------------------------------------------------------------

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
// Fim: Definição das rotas