<!DOCTYPE html>
<html lang="pt-BR">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Estacionamento Inteligente')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Cabeçalho -->
    <header class="bg-dark text-white py-3 mb-4 text-center">
        <div class="container">
            <h1 class="h3 mb-0">CABEÇALHO</h1>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main>
            @yield('content')   
    </main>

    <!-- Rodapé -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            &copy; {{ date('Y') }} RODAPÉ
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>