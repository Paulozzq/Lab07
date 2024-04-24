<?php
session_start();
require 'config/database.php';
$db = new Database();
$con = $db->conectar();
if (empty($_SESSION['usuario'])) {
    header("Location: index.html");
    exit;
}
if ($_SESSION['administrador'] != 1) {
    // Si el usuario no es un administrador, redirigir a una página de acceso denegado
    header("Location: coca.php");
    exit;
}
$registro = [];
if (isset($_POST['editar_id'])) {
    $id = $_POST['editar_id'];
    $query = $con->prepare("SELECT * FROM usuario WHERE id = ?");
    $query->execute([$id]);
    $registro = $query->fetch(PDO::FETCH_ASSOC);
}
if (isset($_POST['editar'])) {
    $correo = $_POST['correo'];
    $administrador = $_POST['administrador'];
    $nombre_usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    if (strlen($nombre) > 50) {
        echo "<script>
                var confirmation = confirm('El nombre debe tener menos de 50 caracteres.');
                if (confirmation) {
                    window.location.href = 'crud.php';
                }
              </script>";
        exit;
    }

    $apellido = $_POST['apellido'];
    if (strlen($apellido) > 50) {
        echo "<script>
                var confirmation = confirm('El apellido debe tener menos de 50 caracteres.');
                if (confirmation) {
                    window.location.href = 'crud.php';
                }
              </script>";
        exit;
    }

    $edad = $_POST['edad'];
    if ($edad < 10 || $edad > 120) {
        echo "<script>
                var confirmation = confirm('La edad debe estar entre 18 y 120 años.');
                if (confirmation) {
                    window.location.href = 'crud.php';
                }
              </script>";
        exit;
    }

    $dni = $_POST['dni'];
    if ($dni < 0) {
        echo "<script>
                var confirmation = confirm('El DNI no puede ser negativo.');
                if (confirmation) {
                    window.location.href = 'crud.php';
                }
              </script>";
        exit;
    }
    if (strlen($dni) != 8) {
        echo "<script>
                var confirmation = confirm('El DNI debe tener exactamente 8 dígitos.');
                if (confirmation) {
                    window.location.href = 'crud.php';
                }
              </script>";
        exit;
    }

    $numero = $_POST['numero'];
    if ($numero < 0 || strlen($numero) != 9) {
        echo "<script>
                var confirmation = confirm('El número no puede ser negativo y debe tener exactamente 9 dígitos.');
                if (confirmation) {
                    window.location.href = 'crud.php';
                }
              </script>";
        exit;
    }

    $pass = $_POST['pass'];
    if (strlen($pass) < 8) {
        echo "<script>
                var confirmation = confirm('La contraseña debe tener al menos 8 caracteres.');
                if (confirmation) {
                    window.location.href = 'crud.php';
                }
              </script>";
        exit;
    }
    $pass_hash = hash('sha256', $pass);
   
    $query = $con->prepare("UPDATE usuario SET nombre = ?, apellido = ?, edad = ?, DNI = ?, numero = ?, correo = ?, administrador = ?, nombre_usuario = ?, pass = ? WHERE id = ?");
    $query->execute([$nombre, $apellido, $edad, $dni, $numero, $correo, $administrador, $nombre_usuario, $pass_hash, $id]);

    header("Location: crud.php");
    exit;
}
?>
<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Registro</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                .mensaje {
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Editar registro</h2>
                <form action="" method="post">
                        <input type="hidden" name="editar_id" value="<?php echo isset($registro['id']) ? $registro['id'] : ''; ?>">
                        <div class="form-group">
                            <label for="nombre_u">Nombre de usuario:</label>
                            <input type="text" class="form-control" id="nombre_u" name="usuario" value="<?php echo $registro['nombre_usuario']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $registro['nombre']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $registro['apellido']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="numero">Número:</label>
                            <input type="number" class="form-control" id="numero" name="numero" value="<?php echo $registro['numero']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edad">Edad:</label>
                            <input type="number" class="form-control" id="edad" name="edad" value="<?php echo $registro['edad']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI:</label>
                            <input type="number" class="form-control" id="dni" name="dni" value="<?php echo $registro['DNI']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $registro['correo']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="contraseña">Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="pass"  required>
                        </div>
                        <div class="form-group">
                            <label for="administrador">Rol:</label>
                            <select class="form-control" id="administrador" name="administrador">
                                <option value="1">Admin</option>
                                <option value="0">User</option>
                            </select>
                </div>
                <button type="submit" class="btn btn-sm btn-danger" name="editar" onclick="return confirm('¿Estás seguro de que deseas editar este registro?')">Editar</button>
                </form>
                <br>
                <a href="crud.php" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas regresar?')">Regresar</a>
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
