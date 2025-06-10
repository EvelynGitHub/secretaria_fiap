<?php

require_once __DIR__ . "/../../vendor/autoload.php";


$openapi = \OpenApi\Generator::scan([
    \OpenApi\Util::finder(__DIR__ . "/../../src/Infra/Http")
]);

echo $openapi->toJson();