<?php

require_once __DIR__ . "/../../vendor/autoload.php";

// header("Content-Type: application/json; charset=UTF-8");
// $openapi = \OpenApi\Generator::scan([__DIR__ . '/../../src/Infra/Http/*']);

// $pattern = '*Controller.php';
// $exclude = ['tests'];


$openapi = \OpenApi\Generator::scan([
    \OpenApi\Util::finder(__DIR__ . "/../../src/Infra/Http")
    // \OpenApi\Util::finder(__DIR__ . "/../../src", $exclude, $pattern)
]);

echo $openapi->toJson();