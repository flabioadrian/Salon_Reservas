<?php
// perfil.php - LÓGICA DE SERVIDOR

// 1. Inicia la sesión y carga la conexión
session_start();
require 'conexion.php'; 

// 2. PROTECCIÓN: Si el usuario NO está logueado, lo envía al login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: login.html");
    exit;
}

// 3. Obtiene el ID del cliente logueado
$id_cliente = $_SESSION['id_cliente'];
$perfil_usuario = []; // Array donde se guardarán los datos

try {
    // 4. CONSULTA SQL: Traer todos los datos del cliente de la tabla 'cliente'
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
    // Manejo de error
    die("Error al cargar datos del perfil."); 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="perfil.css">
</head>
<body>

    <header class="header">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>
    </header>
    
    <section class="perfil-section">

        <h2 class="perfil-title">Mi Perfil</h2>

        <div class="contenedor">
            
            <div class="perfil-info">
                <label>Nombre:</label>
                <input type="text" 
                       value="<?php echo htmlspecialchars($perfil_usuario['nombre'] . ' ' . $perfil_usuario['apellido_paterno'] . ' ' . $perfil_usuario['apellido_materno'] ?? ''); ?>" 
                       readonly>

                <label>Correo:</label>
                <input type="email" 
                       value="<?php echo htmlspecialchars($perfil_usuario['email'] ?? ''); ?>" 
                       readonly>

                <label>Teléfono:</label>
                <input type="text" 
                       value="<?php echo htmlspecialchars($perfil_usuario['telefono'] ?? ''); ?>" 
                       readonly>

                <label>Dirección:</label>
                <input type="text" 
                       value="<?php echo htmlspecialchars($perfil_usuario['direccion'] ?? ''); ?>" 
                       readonly>
            </div>

            <div class="foto">
                <div class="foto">
                <i data-feather="user"></i>
                </div>
                <br>
                <label>Contraseña:</label>
                <input type="password" value="********" readonly> 
            </div>
            
            <div class="perfil-actions">
                <a href="NewContra.html" class="btn perfil-btn">Cambiar Contraseña</a> 
                
                <a href="inicio.html" class="btn perfil-btn">Salir</a>
            </div>

        </div>

    </section>
    
    <footer>
    <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Tenex Reservas</h3>
                    <p>Salas de reunión excepcionales para tus proyectos y eventos. Inspiramos creatividad y productividad en cada espacio.</p>
                </div>
                <div class="footer-column">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#registrar">Reservar</a></li>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Políticas</h3>
                    <ul class="footer-links">
                        <li><a href="#">Términos y Condiciones</a></li>
                        <li><a href="#">Política de Privacidad</a></li>
                        <li><a href="#">Política de Cancelación</a></li>
                        <li><a href="#">Política de Cookies</a></li>
                    </ul>
                </div>
                
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tenex Reservas. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>

<?php $conn->close(); ?>