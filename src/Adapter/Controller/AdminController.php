<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\Controller;

use Exception;
use SecretariaFiap\Core\CasosUso\Admin\InputObject;
use SecretariaFiap\Core\CasosUso\Admin\Login;
use SecretariaFiap\Helpers\HttpResposta;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SecretariaFiap\Helpers\TokenHelper;

class AdminController
{
    public Login $casoUsoLogin;

    public function __construct(Login $casoUsoLogin)
    {
        $this->casoUsoLogin = $casoUsoLogin;
    }

    public function login(Request $request, Response $response): Response
    {
        try {
            $body = $request->getParsedBody();

            $input = InputObject::create($body);

            $output = $this->casoUsoLogin->executar($input);

            $_SESSION['jwt'] = $output->token;

            return HttpResposta::sucesso($output, 200);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }

    /**
     * Logout simplesmente invalidar o token JWT jogando numa blacklist.
     * Isso faz com que o token JWT atual não seja mais válido, forçando o usuário a fazer login novamente.
     * Isso é uma abordagem simples para logout, mesmo assim, não é recomendada para produção.
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Slim\Psr7\Response
     */
    public function logout(Request $request, Response $response): Response
    {
        try {

            $httpHeader = $request->getHeaderLine('Authorization');

            $jwt = explode(' ', $httpHeader)[1] ?? null;

            if (is_null($jwt)) {
                throw new Exception('Token não informado.', 401);
            }

            TokenHelper::adicionarTokenBlacklist($jwt);

            return HttpResposta::sucesso(["mensagem" => "Logout efetuado com sucesso."], 200);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }
}
