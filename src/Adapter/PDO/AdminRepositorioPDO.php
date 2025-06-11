<?php
// filepath: src/Adapter/PDO/AdminRepositorioPDO.php

namespace SecretariaFiap\Adapter\PDO;

use PDO;
use SecretariaFiap\Core\Contratos\Repositorio\AdminRepositorio;
use SecretariaFiap\Core\Entidade\Admin;
use SecretariaFiap\Core\Entidade\Senha;
use SecretariaFiap\Infra\Banco\Conexao;

class AdminRepositorioPDO implements AdminRepositorio
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::obter();
    }

    public function buscarPorEmail(string $email): ?Admin
    {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados) {
            return null;
        }

        $admin = new Admin($dados['nome'], $dados['email'], Senha::criarAPartirDoHash($dados['senha_hash']));
        $admin->setId($dados['id']);
        $admin->setUuid($dados['uuid']);
        return $admin;
    }
}