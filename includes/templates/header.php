<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes raíces</title>
    <link rel="stylesheet" href="/bienesraices/build/css/app.css">
</head>
<body>
    
    <header class = 'header <?php echo $headerIndex ? 'index' : '' ?>'>
        <div class = 'container header-content'>
            <div class = 'bar'>
                <a href="/bienesraices/index.php" class = 'logo'>
                    <h1><span>Bienes</span>Raíces</h1>
                </a>

                <div class = 'mobile-menu'>
                    <img src="/bienesraices/build/img/barras.svg" alt="Icono menu responsive">
                </div>

                <div class = 'derecha'>
                    <img class = 'dark-mode-btn' src="/bienesraices/build/img/dark-mode.svg" alt="Boton dark mode">
                    <nav class = 'navigation'>
                        <a href="/bienesraices/nosotros.php">Nosotros</a>
                        <a href="/bienesraices/anuncios.php">Anuncios</a>
                        <a href="/bienesraices/blog.php">Blog</a>
                        <a href="/bienesraices/contacto.php">Contacto</a>
                    </nav>
                </div>
            </div> <!--- bar --->
        </div>
    </header>