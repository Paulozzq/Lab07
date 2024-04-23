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
    <title>Log-out</title>
</head>
<body>
    <div>
        <h1>Log-out</h1>
        <form method="post">
            <input type="submit" value="Salir" name="salir">
        </form>
    </div>
</body>
</html>
