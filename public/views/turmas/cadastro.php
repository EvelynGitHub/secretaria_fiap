<?php
$title = "Cadastro de Turma";
ob_start();
?>
<h2>Cadastrar Novo Turma</h2>
<form id="turmaForm">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <input type="text" class="form-control" id="descricao" name="descricao" required>
    </div>
    <div class="alert alert-danger d-none" role="alert" id="formError"></div>
    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="/turmas" class="btn btn-secondary">Voltar</a>
</form>


<script>
    $(document).ready(function () {
        $("#turmaForm").on("submit", function (e) {
            e.preventDefault();

            const formData = {
                nome: $("#nome").val(),
                descricao: $("#descricao").val()
            };
            const errorMessage = $("#formError");
            errorMessage.addClass("d-none");

            $.ajax({
                url: "/api/turmas", // Seu endpoint Slim para cadastro de turma
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData),
                success: function (response, status) {
                    //Se não retornar o UUID deu erro
                    if (response.uuid) {
                        alert("Turma cadastrado com sucesso!");
                        window.location.href = "/turmas"; // Redireciona para a listagem
                    } else {
                        errorMessage.text(response.message || "Erro ao cadastrar turma.").removeClass("d-none");
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    errorMessage.text(errorData ? errorData.message : "Ocorreu um erro ao cadastrar turma.").removeClass("d-none");
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