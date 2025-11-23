<?php
// Cambios.php - FORMULARIO DE EDICIÓN

session_start();
require 'conexion.php'; 

// Protección: Redirige si el usuario no está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: login.html");
    exit;
}

$id_cliente = $_SESSION['id_cliente'];
$perfil_usuario = []; 

try {
    // Consulta SQL: Traer todos los datos del cliente logueado
    $sql = "SELECT nombre, apellido_paterno, apellido_materno, telefono, email, direccion 
            FROM cliente 
            WHERE id_cliente = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente); 
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $perfil_usuario = $resultado->fetch_assoc();
    } 

    $stmt->close();

} catch (Exception $e) {
    die("Error al cargar datos del perfil."); 
}

// Bloque para mostrar mensajes de éxito/error después de la actualización
$mensaje_alerta = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $mensaje_alerta = '<div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;">';
    $mensaje_alerta .= '✅ ¡Datos actualizados correctamente!';
    $mensaje_alerta .= '</div>';
} else if (isset($_GET['status']) && $_GET['status'] == 'error') {
    $mensaje_alerta = '<div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;">';
    $mensaje_alerta .= '❌ Error al actualizar los datos. Intente de nuevo.';
    $mensaje_alerta .= '</div>';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="Cambios.css">
</head>
<body>

    <header class="header">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>
    </header>

    <section class="perfil-section">

        <h2 class="perfil-title">Editar Perfil</h2>
        
        <?php echo $mensaje_alerta; ?>

        <form action="procesar_cambios.php" method="POST" class="contenedor">

            <div class="perfil-info">

                <label>Nombre(s):</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($perfil_usuario['nombre'] ?? ''); ?>" required>
                
                <label>Apellido Paterno:</label>
                <input type="text" name="apellido_paterno" value="<?php echo htmlspecialchars($perfil_usuario['apellido_paterno'] ?? ''); ?>" required>

                <label>Apellido Materno:</label>
                <input type="text" name="apellido_materno" value="<?php echo htmlspecialchars($perfil_usuario['apellido_materno'] ?? ''); ?>">

                <label>Correo:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($perfil_usuario['email'] ?? ''); ?>" readonly>

                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($perfil_usuario['telefono'] ?? ''); ?>">

                <label>Dirección:</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($perfil_usuario['direccion'] ?? ''); ?>">
            </div>

            <div class="foto">
                <i data-feather="user"></i>
            </div>

            <div class="perfil-actions">
                <button type="submit" class="btn perfil-btn">Guardar Cambios</button>
            </div>

        </form>
                <a href="perfil.php" class="btn perfil-btn">Descartar Cambios</a>
    </section>
    
    <footer>
    </footer>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>
<?php $conn->close(); ?>