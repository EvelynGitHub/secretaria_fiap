<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Admin;

use SecretariaFiap\Core\Contratos\Repositorio\AdminRepositorio;
use Exception;
use SecretariaFiap\Helpers\TokenHelper;

class Login
{
    private AdminRepositorio $repositorio;

    public function __construct(AdminRepositorio $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    public function executar(InputObject $input): OutputObject
    {
        $admin = $this->repositorio->buscarPorEmail($input->email);

        if (is_null($admin)) {
            throw new Exception("Credenciais inválidas", 401);
        }

        if (!$admin->getSenha()->verificar($input->senha)) {
            throw new Exception("Credenciais inválidas", 401);
        }

        // Aqui poderia adicionar token JWT, refresh token ou outro mecanismo de autenticação
        // Para simplificar, foi feito simplesmente como abaixo

        $token = TokenHelper::gerar($admin->getEmail());

        return OutputObject::create([
            'uuid' => $admin->getUuid(),
            'nome' => $admin->getNome(),
            'token' => $token,
        ]);
    }
}