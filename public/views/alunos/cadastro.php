<?php
$title = "Cadastro de Aluno";
ob_start();
?>
<h2>Cadastrar Novo Aluno</h2>
<form id="alunoForm">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
    </div>
    <div class="alert alert-danger d-none" role="alert" id="formError"></div>
    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="/alunos" class="btn btn-secondary">Voltar</a>
</form>

<?php
$page_scripts = '
<script>
$(document).ready(function() {
    $("#alunoForm").on("submit", function(e) {
        e.preventDefault();

        const formData = {
            nome: $("#nome").val(),
            email: $("#email").val(),
            data_nascimento: $("#data_nascimento").val()
        };
        const errorMessage = $("#formError");
        errorMessage.addClass("d-none");

        $.ajax({
            url: "/api/alunos", // Seu endpoint Slim para cadastro de aluno
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function(response) {
                if (response.success) {
                    alert("Aluno cadastrado com sucesso!");
                    window.location.href = "/alunos"; // Redireciona para a listagem
                } else {
                    errorMessage.text(response.message || "Erro ao cadastrar aluno.").removeClass("d-none");
                }
            },
            error: function(xhr, status, error) {
                const errorData = xhr.responseJSON;
                errorMessage.text(errorData ? errorData.message : "Ocorreu um erro ao cadastrar o aluno.").removeClass("d-none");
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
';
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>