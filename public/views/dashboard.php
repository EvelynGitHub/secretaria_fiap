<?php
$title = "Dashboard";
ob_start();
?>
<div class="jumbotron">
    <h1 class="display-4">Bem-vindo ao Sistema de Alunos!</h1>
    <p class="lead">Navegue pelas opções do menu para gerenciar alunos, turmas e matrículas.</p>
    <hr class="my-4">
    <p>Utilize a barra de navegação acima para acessar as funcionalidades.</p>
    <a class="btn btn-primary btn-lg" href="/alunos" role="button">Gerenciar Alunos</a>
    <a class="btn btn-primary btn-lg" href="/doc" role="button">Documentação da API</a>
    <p>Devido a precessa no desenvolvimento do frontend, o mesmo se encontra instável as vezes. Recomendo executar os
        teste pela documentação da API.</p>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>