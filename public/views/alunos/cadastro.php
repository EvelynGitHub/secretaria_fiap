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
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" required>
    </div>
    <div class="mb-3">
        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
    </div>
    <div class="alert alert-danger d-none" role="alert" id="formError"></div>
    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="/alunos" class="btn btn-secondary">Voltar</a>
</form>


<script>
    $(document).ready(function () {
        $("#alunoForm").on("submit", function (e) {
            e.preventDefault();

            const formData = {
                nome: $("#nome").val(),
                email: $("#email").val(),
                cpf: $("#cpf").val().replace(/\D/g, ''),
                senha: $("#senha").val(),
                data_nascimento: $("#data_nascimento").val()
            };
            const errorMessage = $("#formError");
            errorMessage.addClass("d-none");

            $.ajax({
                url: "/api/alunos", // Seu endpoint Slim para cadastro de aluno
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData),
                success: function (response, status) {
                    //Se não retornar o UUID deu erro
                    if (response.uuid) {
                        alert("Aluno cadastrado com sucesso!");
                        window.location.href = "/alunos"; // Redireciona para a listagem
                    } else {
                        errorMessage.text(response.message || "Erro ao cadastrar aluno.").removeClass("d-none");
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    errorMessage.text(errorData ? errorData.message : "Ocorreu um erro ao cadastrar o aluno.").removeClass("d-none");
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<!-- ';
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?> -->