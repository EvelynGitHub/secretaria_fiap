$(document).ready(function () {
    // Variável para armazenar o 'draw' da requisição atual do Datatables
    // Uso porque não quero modificar meu backend
    let currentDraw = 0;

    var alunosTable = $('#alunosTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/api/alunos",
            "type": "GET",
            "data": function (d) {
                // Guarda o 'draw' da requisição atual antes de modificar 'd'
                currentDraw = d.draw;

                // Mapeia os parâmetros do Datatables para os da API
                d.offset = d.start;
                d.limit = d.length;

                if (d.search && d.search.value) {
                    d.nome = d.search.value;
                } else {
                    d.nome = '';
                }

                // Remove os parâmetros originais do Datatables que a API não usa
                delete d.start;
                delete d.length;
                delete d.search;
                delete d.order;
                delete d.columns;
                delete d.draw; // Remove o 'draw' da requisição para não enviar para API se não for necessário

                return d;
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "dataFilter": function (rawResponse) {

                const parsed = JSON.parse(rawResponse);

                const response = {
                    draw: parseInt(parsed.pagina_atual || 1),
                    recordsTotal: parsed.total_registros,
                    recordsFiltered: parsed.total_registros,
                    data: parsed.itens
                };

                return JSON.stringify(response);
            },
            "error": function (xhr, status, thrown) {
                console.error("Erro ao carregar dados dos alunos:", xhr.responseText);
                alert("Não foi possível carregar os alunos. Por favor, tente novamente.");
            }
        },
        "columns": [
            // { "data": "uuid" },
            { "data": "cpf" },
            { "data": "nome" },
            { "data": "email" },
            { "data": "data_nascimento" },
            {
                "data": null,
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="/alunos/edicao/${row.uuid}" class="btn btn-sm btn-info me-2">Editar</a>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${row.uuid}" data-nome="${row.nome}">Excluir</button>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "searching": true,
        "paging": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        "pageLength": 10
    });

    // Lógica para o modal de exclusão (mantém a mesma)
    $('#alunosTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var nome = $(this).data('nome');
        $('#confirmDeleteModalLabel').text('Confirmação de Exclusão');
        $('#confirmDeleteBody').text(`Tem certeza que deseja excluir o aluno "${nome}" (ID: ${id})?`);
        $('#confirmDeleteButton').data('id', id);
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show();
    });

    $('#confirmDeleteButton').on('click', function () {
        var id = $(this).data('id');
        $.ajax({
            url: `/api/alunos/${id}`,
            method: 'DELETE',
            success: function (response, status) {
                if (status == 'success') {
                    alunosTable.ajax.reload();
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