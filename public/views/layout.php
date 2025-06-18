<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema de Alunos'; ?></title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/datatables.min.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">

    <?php echo $page_css ?? ''; ?>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/datatables.min.js"></script>
    <script src="/js/app.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/dashboard">Sistema de Alunos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/alunos">Alunos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/turmas">Turmas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/matriculas">Matriculas</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logoutButton">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php echo $content ?? ''; // Conteúdo específico da página será injetado aqui ?>
    </div>

    <?php echo $page_scripts ?? ''; ?>
</body>

</html>