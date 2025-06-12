<?php
// Este arquivo vai precisar de um ID de turma para carregar os dados
$uuid = $uuid ?? null; // Obtido do route arguments
$title = "Edição de turma";
ob_start();
?>
<h2>Editar turma</h2>
<form id="turmaEditForm">
    <input type="hidden" id="turmaId" value="<?php echo htmlspecialchars($uuid); ?>">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <input type="text" class="form-control" id="descricao" name="descricao" required>
    </div>
    <div class="alert alert-danger d-none" role="alert" id="formError"></div>
    <button type="submit" class="btn btn-success">Atualizar</button>
    <a href="/turmas" class="btn btn-secondary">Voltar</a>
</form>


<script>
    $(document).ready(function () {
        const turmaId = $("#turmaId").val();
        const errorMessage = $("#formError");

        // Carregar dados do turma ao carregar a página
        if (turmaId) {
            $.ajax({
                url: `/api/turmas/${turmaId}`, // Seu endpoint Slim para buscar turma por ID
                method: "GET",
                success: function (response) {
                    if (response.uuid) {
                        const turma = response;
                        $("#nome").val(turma.nome);
                        $("#descricao").val(turma.descricao);// Certifique-se que o formato é YYYY-MM-DD
                    } else {
                        errorMessage.text(response.message || "turma não encontrado.").removeClass("d-none");
                    }
                },
                error: function (xhr, status, error) {
                    errorMessage.text("Erro ao carregar dados do turma.").removeClass("d-none");
                    console.error(xhr.responseText);
                }
            });
        }

        // Lidar com o envio do formulário de edição
        $("#turmaEditForm").on("submit", function (e) {
            e.preventDefault();

            const formData = {
                nome: $("#nome").val(),
                descricao: $("#descricao").val(),
            };
            errorMessage.addClass("d-none");

            $.ajax({
                url: `/api/turmas/${turmaId}`,
                method: "PUT",
                contentType: "application/json",
                data: JSON.stringify(formData),
                success: function (response) {
                    if (response.uuid) {
                        alert("turma atualizado com sucesso!");
                        window.location.href = "/turmas"; // Redireciona para a listagem
                    } else {
                        errorMessage.text(response.message || "Erro ao atualizar turma.").removeClass("d-none");
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    errorMessage.text(errorData ? errorData.message : "Ocorreu um erro ao atualizar o turma.").removeClass("d-none");
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>