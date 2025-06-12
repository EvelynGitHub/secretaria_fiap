$(document).ready(function () {
    // Lógica para o botão de logout
    $('#logoutButton').on('click', function (e) {
        e.preventDefault();
        if (confirm('Tem certeza que deseja sair?')) {
            $.ajax({
                url: '/api/admin/logout', // Seu endpoint de logout
                method: 'POST',
                success: function (response, status) {
                    console.log("Sucesso no logout")
                    if (status == 'success') {
                        window.location.href = '/login'; // Redireciona para a tela de login
                    } else {
                        alert('Erro ao fazer logout: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert('Ocorreu um erro ao tentar sair. Tente novamente.');
                    console.error(xhr.responseText);
                }
            });
        }
    });

    // Outras funcionalidades globais podem ser adicionadas aqui
});