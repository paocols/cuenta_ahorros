<?php
// Iniciar sesión
session_start();

// Lógica para manejar la solicitud de ID y contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verificar'])) {
    // Obtener ID y clave ingresados
    $id_usuario = $_POST['id_usuario'];
    $clave_usuario = $_POST['clave_usuario'];

    // Conectar a la base de datos
    include 'conexion.php';
    $conexion = new Conexion();
    $conn = $conexion->conn;

    // Consultar la base de datos para verificar el usuario
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe y si la contraseña coincide
    if ($usuario && password_verify($clave_usuario, $usuario['clave'])) {
        // Si la verificación es exitosa, guardamos el ID en la sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        header("Location: formulario_editar.php"); // Redirigir a la página de edición
        exit();
    } else {
        $mensaje = "ID o clave incorrectos. Intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Usuario</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Verificar Usuario</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje">
                <p><?php echo $mensaje; ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulario para ingresar el ID y clave -->
        <form method="POST" action="editar_usuario.php">
            <label for="id_usuario">ID de Usuario:</label>
            <input type="text" id="id_usuario" name="id_usuario" required>

            <label for="clave_usuario">Contraseña:</label>
            <input type="password" id="clave_usuario" name="clave_usuario" required>

            <button type="submit" name="verificar">Verificar</button>
        </form>
    </div>
</body>
</html>
