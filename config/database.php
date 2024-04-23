<?php
class Database{
    private $hostname='localhost';
    private $database='llab07';
    private $username='root';
    private $password='';
    private $pdo; // Propiedad para almacenar la instancia de PDO
    
    function conectar(){
        try{
            $conexion="mysql:host=".$this->hostname.";dbname=".$this->database;
            $option=[
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES=>false,
            ];
            $this->pdo = new PDO($conexion, $this->username, $this->password, $option); // Almacena la instancia de PDO en la propiedad
            return $this->pdo;
        } 
        catch(PDOException $e) {
            echo 'Error de conexion :'.$e->getMessage();
            exit;
        }
    }
    function insertarUsuario($nombre, $apellido, $edad, $dni, $numero, $correo, $administrador, $nombre_usuario, $pass){
        $stmt = $this->pdo->prepare("INSERT INTO usuario (nombre, apellido, edad, dni, numero, correo, administrador, nombre_usuario, pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $edad, $dni, $numero, $correo, $administrador, $nombre_usuario, $pass]);
        return $stmt->rowCount();
    }

    // Read
    function obtenerUsuarios(){
        $stmt = $this->pdo->query("SELECT * FROM usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function obtenerUsuarioPorId($id){
        $stmt = $this->pdo->prepare("SELECT * FROM usuario  WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update
    function actualizarUsuario($id, $nombre, $apellido, $edad, $dni, $numero, $correo, $administrador, $nombre_usuario, $pass){
        $stmt = $this->pdo->prepare("UPDATE usuario SET nombre = ?, apellido = ?, edad = ?, dni = ?, numero = ?, correo = ?, administrador = ?, nombre_usuario = ?, pass = ? WHERE id = ?");
        $stmt->execute([$nombre, $apellido, $edad, $dni, $numero, $correo, $administrador, $nombre_usuario, $pass, $id]);
        return $stmt->rowCount();
    }

    // Delete
    function eliminarUsuario($id){
        $stmt = $this->pdo->prepare("DELETE FROM usuario  WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}


?>
