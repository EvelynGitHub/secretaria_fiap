<?php
$title = "Cadastro de Aluno";
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
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" value="" id="check_cadastro" checked>
            <label class="form-check-label" for="check_cadastro">
                Continuar cadastrando
            </label>
        </div>
    </div>
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

            $.ajax({
                url: "/api/alunos", // Seu endpoint Slim para cadastro de aluno
                method: "POST",
                contentType: "application/json",
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function (response, status) {
                    //Se não retornar o UUID deu erro
                    if (response.uuid) {
                        mostrarToast({
                            mensagem: "Aluno cadastrado com sucesso!",
                            tipo: 'success',
                            tempo: 5000
                        });

                        let continuar = $("#check_cadastro").is(':checked')

                        if (!continuar) {
                            // alert("Aluno cadastrado com sucesso!");                            
                            window.location.href = "/alunos"; // Redireciona para a listagem
                        }

                    } else {
                        mostrarToast({
                            mensagem: response.message || "Erro ao cadastrar aluno.",
                            tipo: 'danger',
                            tempo: 10000
                        });
                    }
                },
                error: function (xhr, status, error) {
                    const errorData = xhr.responseJSON;
                    console.error(xhr.responseText);
                    mostrarToast({
                        mensagem: errorData ? errorData.message : "Ocorreu um erro ao cadastrar o aluno.",
                        tipo: 'danger',
                        tempo: 10000
                    });
                }
            });
        });
    });
</script>