<?php

declare(strict_types=1);

namespace SecretariaFiap\Helpers;

use Psr\Http\Message\ResponseInterface as Response;

class View
{
    public static function renderizar(Response $response, string $viewPath, ?string $layoutPath = null, array $dados = []): Response
    {
        if (is_null($layoutPath))
            $layoutPath = __DIR__ . '/../../public/views/layout.php';// '/views/layout.php';

        extract($dados); // torna cada chave do array uma variável
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        if ($layoutPath) {
            // Define $content como variável usada no layout
            ob_start();
            include $layoutPath;
            $finalOutput = ob_get_clean();
        } else {
            $finalOutput = $content;
        }

        $response->getBody()->write($finalOutput);
        return $response->withHeader('Content-Type', 'text/html');
    }
}
