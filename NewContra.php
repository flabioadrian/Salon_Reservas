<?php
// NewContra.php

session_start();

// Si no está logueado, lo redirigimos al login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: login.html");
    exit;
}

$mensaje_alerta = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $mensaje_alerta = '<div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;">';
    $mensaje_alerta .= '✅ ¡Contraseña cambiada correctamente!';
    $mensaje_alerta .= '</div>';
} 
// Puedes añadir mensajes de error aquí, por ejemplo:
else if (isset($_GET['error']) && $_GET['error'] == 'actual_incorrecta') {
    $mensaje_alerta = '<div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;">';
    $mensaje_alerta .= '❌ Error: La contraseña actual es incorrecta.';
    $mensaje_alerta .= '</div>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio De Contraseña</title>
    <link rel="stylesheet" href="NewContra.css">
</head>
<body>

    <header class="header">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>
    </header>

    <section class="perfil-section">
        <h2 class="perfil-title">Cambiar Contraseña</h2>

        <?php echo $mensaje_alerta; ?>

        <form action="procesar_newcontra.php" method="POST" class="contenedor">
            
            <div class="foto">
                <i data-feather="user"></i>
            </div>

            <div class="password">
                
                <div class="form-group">
                    <label for="password_actual">Contraseña Actual:</label>
                    <input type="password" id="password_actual" name="password_actual" required>
                </div>
                
                <div class="form-group">
                    <label for="password_nueva">Nueva Contraseña:</label>
                    <input type="password" id="password_nueva" name="password_nueva" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmar">Confirme Contraseña:</label>
                    <input type="password" id="password_confirmar" name="password_confirmar" required>
                </div>
                
                <button type="submit" class="btn guardar-btn">Guardar Cambios</button>
                
                <a href="perfil.php" class="btn guardar-btn">Descartar Cambios</a>
            </div>
            
        </form>
    </section>

    <footer>
    </footer>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>

