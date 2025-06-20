<?php

$title = "Edição de turma";

$page_css = <<<HTML
    <link href="/css/select2.min.css" rel="stylesheet">
    <link href="/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="/css/select2-bordas.css" rel="stylesheet">
HTML;

?>

<h1>Matricular Aluno em Turma</h1>

<form method="POST" id="form_matricula">
    <div class="mb-3">
        <label for="uuid_aluno_select2" class="form-label">Aluno:</label>
        <select class="form-control" name="uuid_aluno" id="uuid_aluno_select2" required>
            <option></option>
        </select>
    </div>

    <div class="mb-3">
        <label for="uuid_turma_select2" class="form-label">Turma:</label>
        <select class="form-control" name="uuid_turma" id="uuid_turma_select2" required>
            <option></option>
        </select>

    </div>
    <button type="submit" class="btn btn-success">Matricular</button>
</form>
<hr />

<h2>Alunos por Turma</h2>
<div class="mb-3">
    <label for="filtro_turma" class="form-label">Selecione a Turma:</label>
    <select id="filtro_turma" name="filtro_turma" class="form-select" required>
        <option value="">Selecione um item (obrigatório)</option>
    </select>
</div>


<table id="alunosTurmaTable" class="table table-striped table-bordered" style="width:100%">
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
$page_scripts = <<<HTML
<script src="/js/select2.min.js"></script>
<script src="/js/matriculas.js"></script>
HTML;
?>