<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Login - Sistema de Alunos'; ?></title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
            /* Um cinza claro de fundo */
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 15px;
        }
    </style>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <?php echo $content ?? ''; // Conteúdo específico da página será injetado aqui ?>
    </div>

    <?php echo $page_scripts ?? ''; ?>
</body>

</html>