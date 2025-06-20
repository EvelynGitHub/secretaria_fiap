<?php
// Este arquivo vai precisar de um ID de aluno para carregar os dados
$uuid = $uuid ?? null; // Obtido do route arguments
$title = "Edição de Aluno";
?>
<h2>Editar Aluno</h2>
<form id="alunoEditForm">
    <input type="hidden" id="alunoId" value="<?php echo htmlspecialchars($uuid); ?>">
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
        <label for="senha" class="form-label">Senha <i>(opcional)</i></label>
        <input type="password" class="form-control" id="senha" name="senha">
    </div>
    <div class="alert alert-danger d-none" role="alert" id="formError"></div>
    <button type="submit" class="btn btn-success">Atualizar</button>
    <a href="/alunos" class="btn btn-secondary">Voltar</a>
</form>


<script>
    $(document).ready(function () {
        const alunoId = $("#alunoId").val();
        const errorMessage = $("#formError");

        // Carregar dados do aluno ao carregar a página
        if (alunoId) {
            $.ajax({
                url: `/api/alunos/${alunoId}`,
                method: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.uuid) {
                        const aluno = response;
                        $("#nome").val(aluno.nome);
                        $("#email").val(aluno.email);
                        $("#cpf").val(aluno.cpf);
                        $("#data_nascimento").val(aluno.data_nascimento); // Certifique-se que o formato é YYYY-MM-DD
                    } else {
                        mostrarToast({
                            mensagem: response.message || "Aluno não encontrado.",
                            tipo: 'danger',
                            tempo: 10000
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    mostrarToast({
                        mensagem: errorData ? errorData.message : "Ocorreu um erro ao cadastrar o aluno.",
                        tipo: 'danger',
                        tempo: 10000
                    });
                }
            });
        }

        // Lidar com o envio do formulário de edição
        $("#alunoEditForm").on("submit", function (e) {
            e.preventDefault();

            const formData = {
                nome: $("#nome").val(),
                email: $("#email").val(),
                cpf: $("#cpf").val(),
                senha: $("#senha").val() ?? null,
                data_nascimento: $("#data_nascimento").val()
            };

            $.ajax({
                url: `/api/alunos/${alunoId}`,
                method: "PUT",
                dataType: 'json',
                contentType: "application/json",
                data: JSON.stringify(formData),
                success: function (response) {
                    if (response.uuid) {
                        alert("Aluno atualizado com sucesso!");
                        window.location.href = "/alunos"; // Redireciona para a listagem
                    } else {
                        mostrarToast({
                            mensagem: response.message || "Erro ao atualizar aluno.",
                            tipo: 'danger',
                            tempo: 10000
                        });
                        console.log("Possivelmente EMAIL ou CPF duplicado ao editar aluno.", response)
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    console.error(xhr.responseText);
                    mostrarToast({
                        mensagem: errorData ? errorData.message : "Ocorreu um erro ao atualizar o aluno.",
                        tipo: 'danger',
                        tempo: 10000
                    });
                }
            });
        });
    });
</script>