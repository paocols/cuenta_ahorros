<?php
// Inicializar el saldo
session_start();
if (!isset($_SESSION['saldo'])) {
    $_SESSION['saldo'] = 100.00; // Saldo inicial de la cuenta
}

$mensaje = ''; // Mensaje para mostrar a los usuarios
$saldo = $_SESSION['saldo']; // Obtener saldo desde la sesión

// Procesar el formulario si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si es una operación de depósito
    if (isset($_POST['deposito']) && !empty($_POST['deposito'])) {
        $monto = floatval($_POST['deposito']);
        if ($monto > 0) {
            $_SESSION['saldo'] += $monto; // Actualizar saldo
            $mensaje = "Has depositado S/ " . number_format($monto, 2) . ".";
        } else {
            $mensaje = "Por favor ingresa un monto válido para depositar.";
        }
    }

    // Verificar si es una operación de retiro
    if (isset($_POST['retiro']) && !empty($_POST['retiro'])) {
        $monto = floatval($_POST['retiro']);
        if ($monto > 0 && $monto <= $_SESSION['saldo']) {
            $_SESSION['saldo'] -= $monto; // Actualizar saldo
            $mensaje = "Has retirado S/ " . number_format($monto, 2) . ".";
        } else {
            $mensaje = "Fondos insuficientes o monto inválido.";
        }
    }

    // Actualizar el saldo actual
    $saldo = $_SESSION['saldo'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta de Ahorros</title>
    <link rel="stylesheet" href="cuenta_ahorro.css">
</head>
<body>
    <div class="container">
        <h1>Mi Cuenta de Ahorros</h1>
        
        <!-- Sección de saldo -->
        <div class="saldo">
            <h2>Saldo Actual:</h2>
            <p id="saldo-actual">S/ <?php echo number_format($saldo, 2); ?></p> <!-- Muestrar saldo actual -->
        </div>

        <!-- Mostrar mensaje de operación -->
        <?php if ($mensaje): ?>
            <div class="mensaje">
                <p><?php echo $mensaje; ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulario de operaciones -->
        <form method="POST" action="cuenta_ahorros.php">
            <div class="operacion">
                <label for="deposito">Depositar:</label>
                <input type="number" id="deposito" name="deposito" placeholder="Monto a depositar" step="0.01">
                <button type="submit">Depositar</button>
            </div>

            <div class="operacion">
                <label for="retiro">Retirar:</label>
                <input type="number" id="retiro" name="retiro" placeholder="Monto a retirar" step="0.01">
                <button type="submit">Retirar</button>
            </div>
        </form>
        <br>
        <br>
        <!-- Botón para editar los datos del usuario -->
        <a href="editar_usuario.php">
            <button>Editar Datos</button>
        </a>
    </div>
</body>
</html>

