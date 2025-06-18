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

            // JWT HttpOnly (forma correta de usar o response do slim)
            $response = $response
                ->withHeader(
                    'Set-Cookie',
                    "auth_token={$output->token}; Path=/; HttpOnly; SameSite=None; Secure"
                )
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

            // Para poder testar pelo swagger também
            $response->getBody()->write(json_encode($output));

            return $response;

            // JWT HttpOnly - EXPLICAÇÃO DAS LINHAS
            // Configura o cookie 'auth_token' com o token JWT
            // Path=/      : O cookie estará disponível para todo o domínio
            // HttpOnly    : O cookie não pode ser acessado via JavaScript (segurança XSS)
            // Secure      : O cookie SÓ será enviado se a conexão for HTTPS (ESSENCIAL PARA PRODUÇÃO)
            //     - Secure é obrigatório com SameSite=None
            // SameSite: Protege contra CSRF (pode ser 'Strict' para mais segurança, mas pode impactar links externos)
            //     - SameSite=Lax: impede que o cookie seja enviado em requisições cross-origin (ex: frontend em porta/domínio, diferente do backend).
            //     - SameSite=None: permite envio de cookie entre origens (cross-site).
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }

    /**
     * Logout simplesmente invalidar o token JWT .
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

            // Tenta obter pelo Authorization (para o swagger), se não conseguir tenta pelos cookies
            if (empty($jwt)) {
                // 1. Obter os cookies da requisição
                $cookies = $request->getCookieParams();
                // 2. Extrair o token JWT do cookie 'auth_token'
                $jwt = $cookies['auth_token'] ?? null;
            }

            if (is_null($jwt)) {
                throw new Exception('Token não informado.', 401);
            }

            // Comentado devido ao HttpOnly estar funcionando (e isso tem problemas de permissão)
            // TokenHelper::adicionarTokenBlacklist($jwt);
            // Invalida o cookie 'auth_token' definindo sua data de expiração no passado e deleta o token com auth_token=deleted;
            // As flags (Path, HttpOnly, Secure, SameSite) devem ser as mesmas do cookie original
            // Max-Age=0 apaga o cookie de forma universal.

            $response = $response
                ->withHeader('Set-Cookie', 'auth_token=deleted; Path=/; HttpOnly; Expires=Thu, 01 Jan 1970 00:00:00 GMT; Max-Age=0; SameSite=None; Secure')
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

            $response->getBody()->write(json_encode(['mensagem' => 'Logout com sucesso']));
            return $response;
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }
}
