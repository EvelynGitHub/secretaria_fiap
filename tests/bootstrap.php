<?php

$dumpFile = __DIR__ . '/../dump.testuni.sql';

$pdo = new PDO('sqlite::memory:');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = file_get_contents($dumpFile);

$pdo->exec($sql);

// Define variável global para acesso no repositório
$GLOBALS['__pdo_test__'] = $pdo;
