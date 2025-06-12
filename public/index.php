<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Server\RequestHandlerInterface;
use SecretariaFiap\Adapter\Controller\AdminController;
use SecretariaFiap\Adapter\Controller\AlunoController;
use SecretariaFiap\Adapter\Controller\MatriculaController;
use SecretariaFiap\Adapter\Controller\TurmaController;
use SecretariaFiap\Infra\Http\Middleware;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

session_start();

header("Accept: application/json; charset=UTF-8"); //Aceita apenas coisas enviadas como JSON
header("Content-Type: application/json; charset=UTF-8"); //tipo de retorno

// Inicio: Injeção de dependência 
require_once __DIR__ . "/../config/dependencyInjection.php"; // $container

// $container->set('view', fn() => new PhpRenderer(__DIR__ . '/views'));

AppFactory::setContainer($container);
// Fim: Injeção de dependência 

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);


// ----------------------------------------------------------------------
// Endpoints (FRONTEND) - Estes devem renderizam as views
// ----------------------------------------------------------------------

// Middleware de Autenticação 
$authMiddlewareTokenExiste = function (Request $request, RequestHandlerInterface $handler) {
    $cookies = $request->getCookieParams();
    $token = $cookies['auth_token'] ?? null;

    // Se o token existe segue para o Middleware::class
    if ($token) {
        return $handler->handle($request);
    }
    // Se não existe redireciona
    return (new \Slim\Psr7\Response())->withHeader('Location', '/login')->withStatus(302);
};


$app->get('/', function (Request $request, Response $response) {
    return $response->withHeader('Location', '/login')->withStatus(302);
});

// Rotas Frontend que renderizam as views

$app->get('/login', function (Request $request, Response $response, array $args) {
    ob_start();
    include __DIR__ . '/views/login.php'; // Inclui apenas o formulário de login
    $content = ob_get_clean();

    // Passa o conteúdo do login.php para o layout_login.php
    // e renderiza o layout completo
    ob_start();
    include __DIR__ . '/views/layout_login.php';
    $finalOutput = ob_get_clean();

    $response->getBody()->write($finalOutput);
    return $response->withHeader('Content-Type', 'text/html');
});


// Grupo de rotas protegidas
$app->group('', function (RouteCollectorProxy $group) {
    $group->get('/dashboard', function (Request $request, Response $response, array $args) {
        ob_start();
        include __DIR__ . '/views/dashboard.php';
        $content = ob_get_clean();
        // O dashboard.php já inclui o layout.php, então a renderização acontece lá

        $response->getBody()->write($content);
        return $response->withHeader('Content-Type', 'text/html');
    });

    $group->get('/alunos', function (Request $request, Response $response, array $args) {
        ob_start();
        include __DIR__ . '/views/alunos/listagem.php';
        $content = ob_get_clean();
        // O listagem.php já inclui o layout.php
        $response->getBody()->write($content);
        return $response->withHeader('Content-Type', 'text/html');
    });

    $group->get('/alunos/cadastro', function (Request $request, Response $response, array $args) {
        ob_start();
        include __DIR__ . '/views/alunos/cadastro.php';
        $content = ob_get_clean();

        ob_start();
        include __DIR__ . '/views/layout.php';
        $finalOutput = ob_get_clean();

        $response->getBody()->write($finalOutput);
        return $response->withHeader('Content-Type', 'text/html');
    });

    $group->get('/alunos/edicao/{uuid}', function (Request $request, Response $response, array $args) {
        $uuid = $args['uuid'];
        ob_start();
        include __DIR__ . '/views/alunos/edicao.php';
        $content = ob_get_clean();

        ob_start();
        include __DIR__ . '/views/layout.php';
        $finalOutput = ob_get_clean();

        $response->getBody()->write($finalOutput);
        return $response->withHeader('Content-Type', 'text/html');
    });

    // ... Outras rotas do frontend (turmas, matrículas, etc.)
})->add(Middleware::class)->add($authMiddlewareTokenExiste);


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