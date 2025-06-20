<?php
$title = "Cadastro de Turma";
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

    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" value="" id="check_cadastro" checked>
            <label class="form-check-label" for="check_cadastro">
                Continuar cadastrando
            </label>
        </div>
    </div>
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

            $.ajax({
                url: "/api/turmas", // Seu endpoint Slim para cadastro de turma
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData),
                dataType: "json",
                success: function (response, status) {
                    //Se não retornar o UUID deu erro
                    if (response.uuid) {

                        mostrarToast({
                            mensagem: "Turma cadastrada com sucesso!",
                            tipo: 'success',
                            tempo: 5000
                        });

                        let continuar = $("#check_cadastro").is(':checked')

                        if (!continuar) {
                            alert("Turma cadastrada com sucesso!");
                            window.location.href = "/turmas"; // Redireciona para a listagem
                        }

                    } else {
                        mostrarToast({
                            mensagem: response.message || "Erro ao cadastrar turma.",
                            tipo: 'danger',
                            tempo: 10000
                        });
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    console.error(xhr.responseText);
                    mostrarToast({
                        mensagem: errorData ? errorData.message : "Ocorreu um erro ao cadastrar turma.",
                        tipo: 'danger',
                        tempo: 10000
                    })
                }
            });
        });
    });
</script>