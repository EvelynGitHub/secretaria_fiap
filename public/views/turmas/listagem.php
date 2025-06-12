<?php
$title = "Listagem de turmas";
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Turmas Cadastradas</h2>
    <a href="/turmas/cadastro" class="btn btn-primary">Nova Turma</a>
</div>

<table id="turmasTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descricao</th>
            <th>Total alunos</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<?php include __DIR__ . '/../common/modal_confirm.php'; // Modal de confirmação de exclusão ?>

<?php
$page_scripts = '
<script src="/js/turmas.js"></script>
';
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>