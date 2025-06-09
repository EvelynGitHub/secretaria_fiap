<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SecretariaFiap\Adapter\PDO\AlunoRepositorioPDO;
use SecretariaFiap\Core\CasosUso\Aluno\Cadastrar;
use SecretariaFiap\Core\CasosUso\Aluno\InputObject;

$input = InputObject::create([
    'nome' => 'ana',
    'cpf' => '11111111111',
    'email' => 'ana@exemplo.com.br',
    'senha' => '123ABc12!',
    'data_nascimento' => '09-09-1999'
]);

$respositorio = new AlunoRepositorioPDO();

$casoUsoCadastro = new Cadastrar($respositorio);

$output = $casoUsoCadastro->executar($input);

echo "<pre>";
print_r($output);