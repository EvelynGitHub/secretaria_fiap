<?php

declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';


use SecretariaFiap\Adapter\PDO\EstudanteRepositorioPDO;
use SecretariaFiap\Core\CasosUso\Estudante\Cadastro;
use SecretariaFiap\Core\CasosUso\Estudante\InputObject;

$input = InputObject::create([
    'data_nascimento' => '09-09-1999'
]);

$respositorio = new EstudanteRepositorioPDO();

$casoUsoCadastro = new Cadastro($respositorio);

$output = $casoUsoCadastro->executar($input);

echo "<pre>";
print_r($output);