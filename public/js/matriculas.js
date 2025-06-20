$(document).ready(function () {
    const selectFiltroTurma = $('#filtro_turma');

    // Variável para armazenar o 'draw' da requisição atual do Datatables
    // Uso porque não quero modificar meu backend
    let currentDraw = 0;
    var uuid = $('select[name="filtro-turma"]').val() ?? "";

    // Requisição ajax simples para colocar valor inicial na variável uuid
    $.ajax({
        url: '/api/turmas',
        method: 'GET',
        dataType: 'json',
        data: {
            limit: 1,
            offset: 0
        },
        success: function (response) {
            if (response.itens && response.itens.length > 0) {
                const uuidRetorno = response.itens[0].uuid;

                const newOption = new Option(response.itens[0].nome, uuidRetorno, true, true);

                selectFiltroTurma.append(newOption).trigger('change');

                // ✅ Seta nova URL e carrega a tabela
                const novaUrl = "/api/alunos/turma/" + uuidRetorno;
                alunosTurmaTable.ajax.url(novaUrl).load();
            } else {
                console.error("Erro ao obter o UUID da turma:", response.mensagem);
            }
        },
        error: function (xhr) {
            console.error("Erro ao obter o UUID da turma:", xhr.responseText);
        }
    });


    var alunosTurmaTable = $('#alunosTurmaTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "",
            "type": "GET",
            "data": function (d) {
                currentDraw = d.draw;

                d.offset = d.start;
                d.limit = d.length;
                d.nome = (d.search && d.search.value) ? d.search.value : '';
                // Parametros desnecessários para a API
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
                console.error("Erro ao carregar dados dos alunos:", xhr.responseText);
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
                    return `  `;
                }
            }
        ],
        "searching": true,
        "paging": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        "pageLength": 10,
        "deferLoading": 0 // Não carrega dados inicialmente, apenas quando .load() é chamado
    });



    $("#filtro_turma").on("change", function name(params) {
        uuid = $('select[name="filtro_turma"]').val() ?? "";
        let novaUrl = "/api/alunos/turma/" + uuid;
        alunosTurmaTable.ajax.url(novaUrl);
        alunosTurmaTable.ajax.reload();
    });

    // Lidar com o envio do formulário de matricula
    $("#form_matricula").on("submit", function (e) {
        e.preventDefault();

        let uuidAluno = $('select[name="uuid_aluno"]').val();
        let uuidTurma = $('select[name="uuid_turma"]').val();

        const formData = {
            uuid_aluno: uuidAluno,
            uuid_turma: uuidTurma
        };

        $.ajax({
            url: `/api/matriculas`,
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function (response) {
                const res = JSON.parse(response)

                if (res.sucesso) {
                    alunosTurmaTable.ajax.reload();
                    mostrarToast({
                        mensagem: res.mensagem,
                        tipo: 'success',
                        tempo: 5000
                    });
                } else {
                    mostrarToast({
                        mensagem: res.mensagem,
                        tipo: 'danger',
                        tempo: 5000
                    });
                }
            },
            error: function (xhr, status, error) {
                const errorData = xhr.responseJSON;
                console.error(xhr.responseText);

                mostrarToast({
                    mensagem: errorData ? errorData.message : "Ocorreu um erro.",
                    tipo: 'danger',
                    tempo: 5000
                });
            }
        });


    });



    // Carregamentos de SELECT com SELECT2
    $('#uuid_aluno_select2').select2({
        theme: "bootstrap-5",
        width: '100%',
        placeholder: "Selecione um aluno ou digite para buscar...",
        allowClear: true, // Permite limpar a seleção
        // minimumInputLength: 2, // Começa a buscar após X caracteres digitados
        language: "pt-BR", // Ativa a localização para português
        ajax: {
            url: '/api/alunos', // Seu endpoint de alunos
            dataType: 'json',
            delay: 250, // Atraso em milissegundos antes da requisição
            data: function (params) {
                return {
                    nome: params.term,
                    limit: 10,
                    offset: (params.page || 1) * 10 - 10 // Calcula o offset
                };
            },
            processResults: function (data, params) {
                // Processa a resposta da API para o formato que o Select2 espera
                // 'data' é o JSON retornado pela API
                // { "pagina_atual": 1, "total_registros": 1, "total_paginas": 1, "limite": 10, "itens": [...] }

                params.page = params.page || 1;

                return {
                    results: $.map(data.itens, function (aluno) {
                        // Mapeia cada objeto aluno para o formato { id: ..., text: ... }
                        return {
                            id: aluno.uuid,
                            text: aluno.nome
                        };
                    }),
                    pagination: {
                        // Indica ao Select2 se há mais páginas para carregar
                        more: (params.page * data.limite) < data.total_registros
                    }
                };
            },
            cache: true
        }
    });

    $('#uuid_turma_select2').select2({
        theme: "bootstrap-5",
        width: '100%',
        placeholder: "Selecione um turma ou digite para buscar...",
        allowClear: true,
        language: "pt-BR",
        ajax: {
            url: '/api/turmas',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    nome: params.term,
                    limit: 10,
                    offset: (params.page || 1) * 10 - 10
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.itens, function (turma) {
                        return {
                            id: turma.uuid,
                            text: turma.nome
                        };
                    }),
                    pagination: {
                        more: (params.page * data.limite) < data.total_registros
                    }
                };
            },
            cache: true
        }
    });

    $('#filtro_turma').select2({
        theme: "bootstrap-5",
        width: '100%',
        placeholder: "Selecione um turma ou digite para buscar...",
        allowClear: false,
        language: "pt-BR",
        ajax: {
            url: '/api/turmas',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    nome: params.term,
                    limit: 10,
                    offset: (params.page || 1) * 10 - 10
                };
            },
            processResults: function (data, params) {

                params.page = params.page || 1;

                const results = $.map(data.itens, turma => ({ id: turma.uuid, text: turma.nome }));

                return {
                    results,
                    pagination: {
                        more: (params.page * data.limite) < data.total_registros
                    }
                };
            },
            cache: true
        }
    });


    // Fim dos SELECT2
});