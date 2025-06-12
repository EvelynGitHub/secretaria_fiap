<div class="row justify-content-center">
    <div class="col-12 login-card">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-primary text-white">
                <h3 class="mb-0">Acesso ao Sistema</h3>
            </div>
            <div class="card-body">
                <form id="loginForm" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuário (Email)</label>
                        <input type="email" class="form-control" id="username" name="username"
                            placeholder="seuemail@exemplo.com" value="admin@fiap.com.br" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" value="Admin@123"
                            placeholder="********" required>
                    </div>
                    <div class="alert alert-danger d-none" role="alert" id="loginError">
                    </div>
                    <button type="button" class="btn btn-primary w-100 mb-2" id="btnLogin">Entrar</button>
                </form>
            </div>
            <div class="card-footer text-center text-muted">
                &copy; <?php echo date('Y'); ?> Sistema de Alunos. Todos os direitos reservados.
            </div>
        </div>
    </div>
</div>


<script defer>
    // Este script está embutido no login.php para fins de simplicidade.
    // Em um projeto maior, você pode movê-lo para public/js/login.js

    $(document).ready(function () {

        $('#btnLogin').on('click', login)

        function login() {
            // e.preventDefault();

            const username = $('#username').val();
            const password = $('#password').val();
            const errorMessage = $('#loginError');

            errorMessage.addClass('d-none').text(''); // Esconde mensagens de erro anteriores

            $.ajax({
                url: '/api/admin/login', // Seu endpoint de login do Slim
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ email: username, senha: password }),
                success: function (response, status) {
                    if (status == 'success') {
                        window.location.href = '/dashboard'; // Redireciona para o dashboard após o login
                    } else {
                        errorMessage.text(response.message || 'Credenciais inválidas.').removeClass('d-none');
                    }
                },
                error: function (xhr, status, error) {
                    let message = 'Ocorreu um erro ao tentar fazer login.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    errorMessage.text(message).removeClass('d-none');
                    console.error(xhr.responseText);
                }
            });
        }


        // $('#loginForm').on('submit', function (e) {
        //     e.preventDefault();

        //     const username = $('#username').val();
        //     const password = $('#password').val();
        //     const errorMessage = $('#loginError');

        //     errorMessage.addClass('d-none').text(''); // Esconde mensagens de erro anteriores

        //     $.ajax({
        //         url: '/api/admin/login', // Seu endpoint de login do Slim
        //         method: 'POST',
        //         contentType: 'application/json',
        //         data: JSON.stringify({ username: username, password: password }),
        //         success: function (response) {
        //             if (response.success) {
        //                 window.location.href = '/dashboard'; // Redireciona para o dashboard após o login
        //             } else {
        //                 errorMessage.text(response.message || 'Credenciais inválidas.').removeClass('d-none');
        //             }
        //         },
        //         error: function (xhr, status, error) {
        //             let message = 'Ocorreu um erro ao tentar fazer login.';
        //             if (xhr.responseJSON && xhr.responseJSON.message) {
        //                 message = xhr.responseJSON.message;
        //             }
        //             errorMessage.text(message).removeClass('d-none');
        //             console.error(xhr.responseText);
        //         }
        //     });
        // });
    });
</script>