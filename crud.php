<?php 
session_start();
// Verificar si el usuario está logueado
if (empty($_SESSION['usuario'])) {
    header("Location: index.html");
    exit;
}
if ($_SESSION['administrador'] != 1) {
    // Si el usuario no es un administrador, redirigir a una página de acceso denegado
    header("Location: coca.php");
    exit;
}
// Acción de cerrar sesión
if (isset($_POST['salir'])) {
    session_destroy();
    header("Location: index.html");
    exit;
}
if (isset($_POST['eliminar_id'])) {
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();
    $id = $_POST['eliminar_id'];
    $query = $con->prepare("DELETE FROM usuario WHERE id = ?");
    $query->execute([$id]);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

if (isset($_POST['crear'])){
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();
    $nombre = $_POST['nombre'];
    if (strlen($nombre) > 50) {
         echo "<script>alert('El nombre debe tener menos de 50 caracteres.');</script>";
         header("Location: crud.php");
         exit;
    }

    $apellido = $_POST['apellido'];
    if (strlen($apellido) > 50) {
        echo "<script>alert('El apellido debe tener menos de 50 caracteres.');</script>";
        header("Location: crud.php");
        exit;
    }

    $edad = $_POST['edad'];
    if ($edad < 10 || $edad > 120) {
        echo "<script>alert('La edad debe estar entre 18 y 120 años.');</script>";
        header("Location: crud.php");
        exit;
    }
    
    $dni = $_POST['dni'];
    if (strlen($dni) != 8) {
        echo "<script>alert('El DNI debe tener exactamente 8 dígitos.');</script>";
        exit;
    }
    if ($dni < 0) {
        echo "<script>alert('El dni no puede ser negativo.');</script>";
        header("Location: crud.php");
        exit;
    }

    $numero = $_POST['numero'];
    if ($numero < 0) {
        echo "<script>alert('El número no puede ser negativo.');</script>";
        header("Location: crud.php");
        exit;
    }

    if (strlen($numero) != 9) {
        echo "<script>alert('El número debe tener exactamente 9 dígitos.');</script>";
        exit;
    }
    $correo = $_POST['correo'];
    $administrador = $_POST['administrador'];
    $nombre_usuario = $_POST['usuario'];
    $pass = $_POST['pass'];
    if (strlen($pass) < 8) {
        echo "<script>alert('La contraseña debe tener al menos 8 caracteres.');</script>";
        exit;
    }
    $pass_hash = hash('sha256', $pass);
    $query = $con->prepare("INSERT INTO usuario (nombre, apellido, edad, DNI, numero, correo, administrador, nombre_usuario, pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->execute([$nombre, $apellido, $edad, $dni, $numero, $correo, $administrador, $nombre_usuario,$pass_hash]);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
require 'config/database.php';
$db = new Database();
$con = $db->conectar();
$sql = $con->query("SELECT * FROM usuario");
$registros = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Acciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .mensaje {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Menú de Acciones</h1>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <!-- Formulario para seleccionar acción -->
            <form action="" method="post" class="mr-3">
                <div class="form-group">
                    <label for="accion">Selecciona una acción:</label>
                    <select class="form-control" id="accion" name="accion">
                        <option value="mostrar">Mostrar</option>
                        <option value="crear">Crear</option>
                        <option value="editar">Editar</option>
                        <option value="eliminar">Eliminar</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="enviar">Enviar</button>
            </form>
            <!-- Formulario para cerrar sesión -->
            <form method="post">
                <button type="submit" class="btn btn-danger" name="salir">Salir</button>
                <a href="coca.php" class="btn btn-danger">Ver Blog</a>
            </form>
        </div>
        <br>
        <br>
        <?php
        if (isset($_POST['enviar'])){
            $accion = $_POST['accion'];
            if ($accion == 'mostrar'){
        ?>
                <h2>Registros Existentes</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Número</th>
                            <th>Edad</th>
                            <th>DNI</th>
                            <th>Correo</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro) : ?>
                        <tr>
                            <td><?php echo $registro['id']; ?></td>
                            <td><?php echo $registro['nombre_usuario']; ?></td>
                            <td><?php echo $registro['nombre']; ?></td>
                            <td><?php echo $registro['apellido']; ?></td>
                            <td><?php echo $registro['numero']; ?></td>
                            <td><?php echo $registro['edad']; ?></td>
                            <td><?php echo $registro['DNI']; ?></td>
                            <td><?php echo $registro['correo']; ?></td>
                            <td><?php echo $registro['administrador'] == 1 ? 'Admin' : 'User'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
        <?php
            if ($accion == 'eliminar'){
            ?>
                <h2>Registros Existentes</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Número</th>
                            <th>Edad</th>
                            <th>DNI</th>
                            <th>Correo</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro) : ?>
                        <tr>
                            <td><?php echo $registro['id']; ?></td>
                            <td><?php echo $registro['nombre_usuario']; ?></td>
                            <td><?php echo $registro['nombre']; ?></td>
                            <td><?php echo $registro['apellido']; ?></td>
                            <td><?php echo $registro['numero']; ?></td>
                            <td><?php echo $registro['edad']; ?></td>
                            <td><?php echo $registro['DNI']; ?></td>
                            <td><?php echo $registro['correo']; ?></td>
                            <td><?php echo $registro['administrador'] == 1 ? 'Admin' : 'User'; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="eliminar_id" value="<?php echo $registro['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
        <?php
            if ($accion == 'editar'){
            ?>
                <h2>Registros Existentes</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Número</th>
                            <th>Edad</th>
                            <th>DNI</th>
                            <th>Correo</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro) : ?>
                        <tr>
                            <td><?php echo $registro['id']; ?></td>
                            <td><?php echo $registro['nombre_usuario']; ?></td>
                            <td><?php echo $registro['nombre']; ?></td>
                            <td><?php echo $registro['apellido']; ?></td>
                            <td><?php echo $registro['numero']; ?></td>
                            <td><?php echo $registro['edad']; ?></td>
                            <td><?php echo $registro['DNI']; ?></td>
                            <td><?php echo $registro['correo']; ?></td>
                            <td><?php echo $registro['administrador'] == 1 ? 'Admin' : 'User'; ?></td>
                            <td>
                            <td>
                                <form action="editar.php" method="post">
                                    <input type="hidden" name="editar_id" value="<?php echo $registro['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Editar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
        <?php
            if ($accion == 'crear'){
            ?>
                <div class="container">
                    <h2>Crear Nuevo Registro</h2>
                    <form action="?accion=crear" method="post">
                        <div class="form-group">
                            <label for="nombre_u">Nombre de usuario:</label>
                            <input type="text" class="form-control" id="nombre_u" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="numero">Número:</label>
                            <input type="number" class="form-control" id="numero" name="numero" required>
                        </div>
                        <div class="form-group">
                            <label for="edad">Edad:</label>
                            <input type="number" class="form-control" id="edad" name="edad" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI:</label>
                            <input type="number" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="contraseña">Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="pass" required>
                        </div>
                        <div class="form-group">
                            <label for="administrador">Rol:</label>
                            <select class="form-control" id="administrador" name="administrador">
                                <option value="1">Admin</option>
                                <option value="0">User</option>
                            </select>
                </div>
                <button type="submit" class="btn btn-sm btn-danger" name="crear" onclick="return confirm('¿Estás seguro de que deseas crear este registro?')">Crear</button>
        </form>
        <?php
        }
        ?>
        <?php
        }
        ?>
    </div>
    <script>
        document.getElementById('nombre_u').addEventListener('input', function() {
            var username = this.value.trim();
            var regex = /^[a-zA-Z0-9_-]+$/;
            var isValid = regex.test(username);
            var feedback = document.getElementById('usernameHelpBlock');
            var inputField = this;
            
            if (isValid) {
                inputField.classList.remove('is-invalid');
                feedback.innerText = 'Se permiten letras, números, _ y -';
            } else {
                inputField.classList.add('is-invalid');
                feedback.innerText = 'Por favor, ingrese un nombre de usuario válido.';
            }
        });
        document.getElementById('nombre').addEventListener('input', function() {
            var name = this.value.trim();
            var regex = /^[a-zA-Z\s]*$/;
            var isValid = regex.test(name);
            var inputField = this;
            
            if (isValid) {
                inputField.classList.remove('is-invalid');
            } else {
                inputField.classList.add('is-invalid');
            }
        });
        document.getElementById('apellido').addEventListener('input', function() {
            var apellido = this.value.trim();
            var regex = /^[a-zA-Z\s]*$/;
            var isValid = regex.test(apellido);
            var inputField = this;
            
            if (isValid) {
                inputField.classList.remove('is-invalid');
            } else {
                inputField.classList.add('is-invalid');
            }
        });
        document.getElementById('numero').addEventListener('input', function() {
            var numero = this.value.trim();
            var regex = /^\d{9}$/;
            var isValid = regex.test(numero);
            var inputField = this;
            
            if (isValid) {
                inputField.classList.remove('is-invalid');
            } else {
                inputField.classList.add('is-invalid');
            }
        });
    </script>
</body>
</html>
