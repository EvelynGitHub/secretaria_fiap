<?php
// Este arquivo vai precisar de um ID de turma para carregar os dados
$uuid = $uuid ?? null; // Obtido do route arguments
$title = "Edição de turma";
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
    <button type="submit" class="btn btn-success">Atualizar</button>
    <a href="/turmas" class="btn btn-secondary">Voltar</a>
</form>


<script>
    $(document).ready(function () {
        const turmaId = $("#turmaId").val();

        // Carregar dados do turma ao carregar a página
        if (turmaId) {
            $.ajax({
                url: `/api/turmas/${turmaId}`,
                method: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.uuid) {
                        const turma = response;
                        $("#nome").val(turma.nome);
                        $("#descricao").val(turma.descricao);
                    } else {
                        mostrarToast({
                            mensagem: response.message || "Turma não encontrada.",
                            tipo: 'danger',
                            tempo: 10000
                        });
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    console.error(xhr.responseText);
                    mostrarToast({
                        mensagem: errorData ? errorData.message : "Erro ao carregar dados do turma.",
                        tipo: 'danger',
                        tempo: 10000
                    });
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

            $.ajax({
                url: `/api/turmas/${turmaId}`,
                method: "PUT",
                dataType: "json",
                contentType: "application/json",
                data: JSON.stringify(formData),
                success: function (response) {
                    console.log(response);

                    if (response.uuid) {
                        mostrarToast({
                            mensagem: "Turma cadastrada com sucesso!",
                            tipo: 'success',
                            tempo: 5000
                        });

                        let continuar = $("#check_cadastro").is(':checked')

                        if (!continuar) {
                            alert("Turma atualizada com sucesso!");
                            window.location.href = "/turmas"; // Redireciona para a listagem
                        }
                    } else {
                        mostrarToast({
                            mensagem: response.message || "Erro ao atualizar turma.",
                            tipo: 'danger',
                            tempo: 10000
                        });
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    console.error(xhr.responseText);
                    mostrarToast({
                        mensagem: errorData ? errorData.message : "Ocorreu um erro ao tualizar turma..",
                        tipo: 'danger',
                        tempo: 10000
                    });
                }
            });
        });
    });
</script>