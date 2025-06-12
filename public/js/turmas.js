$(document).ready(function () {
    let currentDraw = 0;

    var turmasTable = $('#turmasTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/api/turmas",
            "type": "GET",
            "data": function (d) {
                currentDraw = d.draw;

                d.offset = d.start;
                d.limit = d.length;

                if (d.search && d.search.value) {
                    d.nome = d.search.value;
                } else {
                    d.nome = '';
                }

                delete d.start;
                delete d.length;
                delete d.search;
                delete d.order;
                delete d.columns;
                delete d.draw;

                return d;
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "dataFilter": function (rawResponse) {

                const parsed = JSON.parse(rawResponse);

                const response = {
                    draw: currentDraw,
                    recordsTotal: parsed.total_registros,
                    recordsFiltered: parsed.total_registros,
                    data: parsed.itens
                };

                return JSON.stringify(response);
            },
            "error": function (xhr, status, thrown) {
                console.error("Erro ao carregar dados dos turmas:", xhr.responseText);
                alert("Não foi possível carregar os turmas. Por favor, tente novamente.");
            }
        },
        "columns": [
            { "data": "nome" },
            { "data": "descricao" },
            { "data": "qtd_alunos" },
            {
                "data": null,
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="/turmas/edicao/${row.uuid}" class="btn btn-sm btn-info me-2">Editar</a>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${row.uuid}" data-nome="${row.nome}">Excluir</button>
                    `;
                }
            }
        ],
        "searching": true,
        "paging": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        "pageLength": 10
    });

    // Lógica para o modal de exclusão (mantém a mesma)
    $('#turmasTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var nome = $(this).data('nome');
        $('#confirmDeleteModalLabel').text('Confirmação de Exclusão');
        $('#confirmDeleteBody').text(`Tem certeza que deseja excluir a turma "${nome}" (ID: ${id})?`);
        $('#confirmDeleteButton').data('id', id);
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show();
    });

    $('#confirmDeleteButton').on('click', function () {
        var id = $(this).data('id');
        $.ajax({
            url: `/api/turmas/${id}`,
            method: 'DELETE',
            success: function (response, status) {
                if (status == 'success') {
                    turmasTable.ajax.reload();
                    var confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                    confirmModal.hide();
                    alert('Turma excluída com sucesso!');
                } else {
                    alert('Erro ao excluir turma: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                alert('Ocorreu um erro ao excluir o turma. Tente novamente.');
                console.error(xhr.responseText);
            }
        });
    });
});