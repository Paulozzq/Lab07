<?php
session_start();
require 'config/database.php';
$db = new Database();
$con = $db->conectar();
if(isset($_POST['Enviar'])) {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $hashed_password = hash('sha256', $contraseña);
    $sql = $con->prepare("SELECT nombre_usuario,pass FROM usuario WHERE nombre_usuario = :usuario AND pass = :pass;");
    $sql->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $sql->bindParam(':pass', $hashed_password, PDO::PARAM_STR);
    $sql->execute();
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    if($resultado) {   
        $correcto = true;
    }else {
        $correcto = false;
    }
    if($correcto) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['contraseña'] = $hashed_password;
        header("Location: coca.php");
    }else
    {
       echo 'Usuario o contraseña incorrectos';
    }
}
?>