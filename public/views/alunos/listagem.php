<?php
$title = "Listagem de Alunos";
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Alunos Cadastrados</h2>
    <a href="/alunos/cadastro" class="btn btn-primary">Novo Aluno</a>
</div>

<table id="alunosTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>CPF</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Data de Nascimento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<?php include __DIR__ . '/../common/modal_confirm.php'; // Modal de confirmação de exclusão ?>

<?php
$page_scripts = '
<script src="/js/alunos.js"></script>
';
?>