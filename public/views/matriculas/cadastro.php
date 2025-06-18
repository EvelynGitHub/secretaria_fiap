<?php
$alunos = $alunos ?? null;
$turmas = $turmas ?? null; // Obtido do route arguments
$title = "Edição de turma";

?>
<h1>Matricular Aluno em Turma</h1>
<p>Não deu tempo de deixar bonito </p>
<form method="POST" id="formMatricula">
    <div class="mb-3">
        <label for="uuid_aluno" class="form-label">Aluno:</label>
        <select name="uuid_aluno" class="form-select" required>
            <option selected contentEditable> Digite aqui
            </option>
            <?php foreach ($alunos as $aluno): ?>
                <option value="<?= $aluno->uuid ?>">
                    <?= ($aluno->nome) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="uuid_turma" class="form-label">Turma:</label>
        <select name="uuid_turma" class="form-select" required>
            <?php foreach ($turmas as $turma): ?>
                <option value="<?= $turma->uuid ?>">
                    <?= ($turma->nome) ?>
                </option>
            <?php endforeach; ?>
        </select>

    </div>
    <button type="submit" class="btn btn-success">Matricular</button>
</form>

<hr />

<h2>Alunos por Turma</h2>
<div class="mb-3">
    <label for="filtro-turma" class="form-label">Selecione a Turma:</label>
    <select id="filtro-turma" name="filtro-turma" class="form-select">
        <option value="">Selecione</option>
        <?php if (count($turmas) > 0): ?>
            <option value="<?= $turmas[0]->uuid ?>" selected>
                <?= ($turmas[0]->nome) ?>
            </option>
        <?php endif; ?>
        <?php for ($i = 1; $i < count($turmas); $i++): ?>
            <option value="<?= $turmas[$i]->uuid ?>">
                <?= ($turmas[$i]->nome) ?>
            </option>
        <?php endfor; ?>
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
$page_scripts = '
<script src="/js/matriculas.js"></script>
';
?>