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
function mostrarToast({ mensagem, tipo = 'primary', tempo = 5000 }) {
    const container = document.getElementById('toastContainer');

    const id = `toast-${Date.now()}`;

    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${tipo} border border-${tipo} position-relative`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.setAttribute('id', id);
    toastEl.setAttribute('data-bs-delay', tempo);

    toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${mensagem}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-progress" style="animation-duration: ${tempo}ms;"></div>
            `;

    container.appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl);
    toast.show();

    // Remove do DOM depois que sumir
    setTimeout(() => {
        toastEl.remove();
    }, tempo + 500);
}