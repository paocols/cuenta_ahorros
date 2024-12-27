<?php
session_start(); // Inicia la sesión
require_once 'conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $clave = trim($_POST['clave']);

    if (empty($email) || empty($clave)) {
        echo "<p>Todos los campos son obligatorios.</p>";
    } else {
        try {
            // Crear conexión
            $conexion = new Conexion();
            $conn = $conexion->conn;

            // Verificar si el usuario existe
            $sql = "SELECT id, nombre, clave FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                // Verificar la contraseña
                if (password_verify($clave, $usuario['clave'])) {
                    // Inicia sesión
                    header("Location: cuenta_ahorros.php");
                    exit();
                } else {
                    echo "<p>Contraseña incorrecta.</p>";
                }
            } else {
                echo "<p>El usuario no existe.</p>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "<p>Acceso denegado.</p>";
}
?>
