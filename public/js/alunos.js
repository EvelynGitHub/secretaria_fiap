$(document).ready(function () {
    var alunosTable = $('#alunosTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/api/alunos", // Seu endpoint Slim para listar alunos paginados
            "type": "GET",
            "data": function (d) {
                // Parâmetros que o Datatables envia (draw, start, length, search[value], order[0][column], order[0][dir])
                // O Slim framework pode precisar de ajustes para entender esses parâmetros.
                // Ex: d.start, d.length, d.search.value
            },
            "dataSrc": function (json) {
                // O servidor deve retornar um JSON com { draw: ..., recordsTotal: ..., recordsFiltered: ..., data: [...] }
                return json.data;
            },
            "error": function (xhr, error, thrown) {
                console.error("Erro ao carregar dados dos alunos:", xhr.responseText);
                alert("Não foi possível carregar os alunos. Por favor, tente novamente.");
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "nome" },
            { "data": "email" },
            { "data": "data_nascimento" },
            {
                "data": null,
                "render": function (data, type, row) {
                    return `
                        <a href="/alunos/edicao/${row.id}" class="btn btn-sm btn-info me-2">Editar</a>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${row.id}" data-nome="${row.nome}">Excluir</button>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });

    // Lidar com o botão de exclusão
    $('#alunosTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var nome = $(this).data('nome');
        $('#confirmDeleteModalLabel').text('Confirmação de Exclusão');
        $('#confirmDeleteBody').text(`Tem certeza que deseja excluir o aluno "${nome}" (ID: ${id})?`);
        $('#confirmDeleteButton').data('id', id); // Armazena o ID no botão de confirmação
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show();
    });

    // Lidar com a confirmação de exclusão no modal
    $('#confirmDeleteButton').on('click', function () {
        var id = $(this).data('id');
        $.ajax({
            url: `/api/alunos/${id}`, // Seu endpoint Slim para exclusão de aluno
            method: 'DELETE',
            success: function (response) {
                if (response.success) {
                    alunosTable.ajax.reload(); // Recarrega a tabela após a exclusão
                    var confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                    confirmModal.hide();
                    alert('Aluno excluído com sucesso!');
                } else {
                    alert('Erro ao excluir aluno: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                alert('Ocorreu um erro ao excluir o aluno. Tente novamente.');
                console.error(xhr.responseText);
            }
        });
    });
});