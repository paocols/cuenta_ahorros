<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Por favor, inicie sesión primero.'); window.location.href = 'index.html';</script>";
    exit();
}

// Conectar a la base de datos
include 'conexion.php';
$conexion = new Conexion();
$conn = $conexion->conn;

// Obtener los datos del usuario desde la base de datos
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $usuario_id);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuario) {
    echo "<script>alert('No se encontró el usuario.'); window.location.href = 'index.html';</script>";
    exit();
}

// Inicializar mensaje de operación
$mensaje = '';

// Lógica para actualizar los datos del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_email = $_POST['email'];
    $nueva_clave = $_POST['clave'];

    // Si se ha ingresado una nueva contraseña, encriptarla
    if (!empty($nueva_clave)) {
        $nueva_clave = password_hash($nueva_clave, PASSWORD_DEFAULT);
    } else {
        // Mantener la misma contraseña si no se ingresa una nueva
        $nueva_clave = $usuario['clave'];
    }

    // Actualizar los datos del usuario en la base de datos
    $update_query = "UPDATE usuarios SET nombre = :nombre, email = :email, clave = :clave WHERE id = :id";
    $stmt = $conn->prepare($update_query);
    $stmt->bindParam(':nombre', $nuevo_nombre);
    $stmt->bindParam(':email', $nuevo_email);
    $stmt->bindParam(':clave', $nueva_clave);
    $stmt->bindParam(':id', $usuario_id);

    if ($stmt->execute()) {
        $mensaje = "Datos actualizados correctamente.";
        
        // Redirigir a la página de inicio después de la actualización
        header("Location: index.html"); // O la URL que desees para la página de inicio
        exit();
    } else {
        $mensaje = "Error al actualizar los datos. Inténtalo nuevamente.";
    }
}

// Lógica para eliminar al usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $delete_query = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($delete_query);
    $stmt->bindParam(':id', $usuario_id);

    if ($stmt->execute()) {
        // Destruir la sesión y redirigir al usuario a la página principal
        session_destroy();
        echo "<script>alert('Usuario eliminado correctamente.'); window.location.href = 'index.html';</script>";
        exit();
    } else {
        $mensaje = "Error al eliminar el usuario. Inténtalo nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Editar Datos del Usuario</h1>

        <?php if ($mensaje): ?>
            <div class="mensaje">
                <p><?php echo $mensaje; ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulario de edición de datos -->
        <form method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>

            <label for="clave">Contraseña:</label>
            <input type="password" id="clave" name="clave" placeholder="Nueva contraseña (opcional)">

            <button type="submit" name="actualizar">Actualizar</button>
        </form>

        <!-- Botón para eliminar usuario -->
        <form method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta?');">
            <button type="submit" name="eliminar" class="btn-eliminar">Eliminar Cuenta</button>
        </form>
    </div>
</body>
</html>
