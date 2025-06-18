$(document).ready(function () {
    // Variável para armazenar o 'draw' da requisição atual do Datatables
    // Uso porque não quero modificar meu backend
    let currentDraw = 0;
    var uuid = $('select[name="filtro-turma"]').val() ?? "";

    if (uuid) {
        var alunosTurmaTable = $('#alunosTurmaTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/api/alunos/turma/" + uuid,
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

                    console.log(parsed)
                    const response = {
                        draw: currentDraw,
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
                        return ` nada
                       `;
                    }
                }
            ],
            "searching": true,
            "paging": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            "pageLength": 10
        });
    }


    $("#filtro-turma").on("change", function name(params) {

        uuid = $('select[name="filtro-turma"]').val() ?? "";

        let novaUrl = "/api/alunos/turma/" + uuid;
        alunosTurmaTable.ajax.url(novaUrl);
        alunosTurmaTable.ajax.reload();
    });

    // Lidar com o envio do formulário de edição
    $("#formMatricula").on("submit", function (e) {
        e.preventDefault();

        let uuidAluno = $('select[name="uuid_aluno"]').val();
        let uuidTurma = $('select[name="uuid_turma"]').val();

        // Loga os valores para depuração (você pode remover isso depois)
        console.log('UUID do Aluno selecionado:', uuidAluno);
        console.log('UUID da Turma selecionada:', uuidTurma);

        const formData = {
            uuid_aluno: uuidAluno,
            uuid_turma: uuidTurma
        };
        // errorMessage.addClass("d-none");

        $.ajax({
            url: `/api/matriculas`,
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function (response) {
                console.log("Aqui", response)

                // alunosTurmaTable.ajax.reload();
                if (response.sucesso) {
                    alert(response.mensagem);
                } else {

                    alert(response.mensagem);
                    // errorMessage.text(response.message || "Erro .").removeClass("d-none");
                }
            },
            error: function (xhr, status, error) {
                const errorData = xhr.responseJSON;
                // errorMessage.text(errorData ? errorData.message : "Ocorreu um erro.").removeClass("d-none");
                console.error(xhr.responseText);
            }
        });


    });
});