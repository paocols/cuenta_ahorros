<?php
// Incluir archivo de conexión
include 'conexion.php';

// Crear instancia de la clase Conexion para obtener la conexión
$conexion = new Conexion();
$conn = $conexion->conn; // Asignar la conexión a la variable $conn

// Procesar el formulario si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];
    $clave_confirmar = $_POST['clave_confirmar'];

    // Validar que las contraseñas coincidan
    if ($clave !== $clave_confirmar) {
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
    } else {
        try {
            // Encriptar la contraseña
            $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

            // Preparar la consulta SQL
            $sql = "INSERT INTO usuarios (nombre, email, clave) VALUES (:nombre, :email, :clave)";
            $stmt = $conn->prepare($sql);

            // Vincular los valores
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':clave', $clave_encriptada);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.');</script>";
                // Redirigir a la página de inicio de sesión
                header("Location: index.html");
                exit();
            } else {
                echo "<script>alert('Error al registrar el usuario. Inténtalo nuevamente.');</script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
