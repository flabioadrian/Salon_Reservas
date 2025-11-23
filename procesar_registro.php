<?php
// procesar_registro.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y limpiar los datos del formulario
    $nombres = trim($_POST['nombre']);
    $apellido_paterno = trim($_POST['apellido_paterno']);
    $apellido_materno = trim($_POST['apellido_materno']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $direccion = trim($_POST['direccion']);
    
    // Validaciones básicas
    $errores = [];
    
    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del email no es válido";
    }
    
    // Validar que no haya espacios al inicio o final en los campos requeridos
    if ($nombres !== trim($nombres) || $apellido_paterno !== trim($apellido_paterno) || 
        $apellido_materno !== trim($apellido_materno) || $password !== trim($password)) {
        $errores[] = "Los campos no pueden tener espacios al inicio o final";
    }
    
    // Validar que el teléfono tenga 10 dígitos
    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $errores[] = "El teléfono debe tener 10 dígitos";
    }
    
    // Si no hay errores, procesar el registro
    if (empty($errores)) {
        // Aquí iría la lógica para guardar el usuario en la base de datos
        // Por ejemplo:
        // - Hashear la contraseña: $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // - Guardar en la base de datos
        
        // Después del registro exitoso, redirigir al login
        header("Location: login.html?registro=exitoso");
        exit();
    } else {
        // Si hay errores, mostrar mensajes
        foreach ($errores as $error) {
            echo "<p>Error: $error</p>";
        }
    }
} else {
    // Si alguien intenta acceder directamente al archivo sin enviar el formulario
    header("Location: registro.html");
    exit();
}
?>