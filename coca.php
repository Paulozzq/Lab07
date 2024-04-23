<?php 
session_start();

if(empty($_SESSION['usuario'])) {
    header("Location: index.html");
    exit;
}

if(isset($_POST['salir'])){
    session_destroy();
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido <?php $_SESSION['usuario'] ?> </title>
</head>
<body>
<div class="container">
        <h1>Bienvenido <?php echo $_SESSION['usuario']; ?> </h1>

        <form method="post">
            <input type="submit" value="Salir" name="salir" class="logout-button">
        </form>
    </div>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog de Coca-Cola</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
 


header, nav, footer {
    background-color: #dc143c; 
    color: #fff;
    text-align: center;
    padding: 10px 0;
}

nav ul {
    list-style-type: none;
    padding: 0;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

section.blog-posts {
    padding: 20px;
}

article {
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
}

article h2 {
    color: #dc143c;
}

footer {
    text-align: center;
    padding: 10px 0;
    background-color: #333;
    color: #fff;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    align-items: center;
}

h1 {
    margin: 0;
    text-align: center;
    align-items: center;
    opacity: 10;
    font-family: 'Courier New', Courier, monospace;
}

.logout-button {
    background-color: #dc143c;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}


</style>

<body>
    <header>
        <h1>Blog de Coca-Cola</h1>
    </header>

    <nav>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Sobre nosotros</a></li>
            <li><a href="#">Productos</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>

    <section class="blog-posts">
        <article>
            <h2>Nuevo lanzamiento: Coca-Cola Zero Sugar</h2>
            <p>Descubre nuestro último producto: Coca-Cola Zero Sugar, la misma refrescante Coca-Cola, sin azúcar.</p>
        </article>

        <article>
            <h2>Historia de Coca-Cola</h2>
            <p>Conoce la fascinante historia detrás de la marca Coca-Cola, desde sus humildes comienzos hasta convertirse en una de las marcas más reconocidas a nivel mundial.</p>
        </article>

        <article>
            <h2>Receta de cóctel: Coca-Cola con limón</h2>
            <p>¡Aprende a preparar un delicioso cóctel con Coca-Cola y limón para sorprender a tus amigos en tu próxima reunión!</p>
        </article>

        <article>
            <h2>Beneficios de Coca-Cola Light</h2>
            <p>Descubre los beneficios de Coca-Cola Light y por qué es la elección perfecta para aquellos que buscan una opción baja en calorías.</p>
        </article>

        <article>
            <h2>Impacto ambiental de Coca-Cola</h2>
            <p>Conoce las iniciativas de sostenibilidad de Coca-Cola y nuestro compromiso con el medio ambiente.</p>
        </article>

        <article>
            <h2>Top 10 anuncios memorables de Coca-Cola</h2>
            <p>Revive los anuncios más icónicos de Coca-Cola que han dejado una huella en la cultura popular.</p>
        </article>
    </section>

    <footer>
        <p>&copy; 2024 Blog de Coca-Cola</p>
    </footer>
</body>
</html>
