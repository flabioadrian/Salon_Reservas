<?php
// procesar_registro.php

// 1. Incluir el archivo de conexión
require 'conexion.php'; 

// 2. Verificar que el método es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoger y limpiar los datos del formulario (usamos trim para limpiar espacios iniciales/finales)
    $nombres = trim($_POST['nombre']);
    $apellido_paterno = trim($_POST['apellido_paterno']);
    $apellido_materno = trim($_POST['apellido_materno']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // No limpiar la contraseña con trim($password) aquí, ya que validaste los espacios
    $direccion = trim($_POST['direccion']);
    
    // Inicializar array de errores
    $errores = [];
    
    // --- TUS VALIDACIONES BÁSICAS ---
    
    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del email no es válido";}
    
    // Validar que el teléfono tenga 10 dígitos
    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $errores[] = "El teléfono debe tener 10 dígitos";
    }
    
    // Validar que la contraseña no esté vacía
    if (empty($password)) {
        $errores[] = "La contraseña no puede estar vacía";
    }
    
   
    if (empty($errores)) {
        
        try {
            // 3. VERIFICAR SI EL EMAIL YA EXISTE (seguridad y unicidad)
            $sql_check = "SELECT id_cliente FROM cliente WHERE email = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $resultado_check = $stmt_check->get_result();

            if ($resultado_check->num_rows > 0) {
                $errores[] = "El email ya está registrado. Intente iniciar sesión.";
            }
            
            $stmt_check->close();

        } catch (Exception $e) {
            $errores[] = "Error de base de datos al verificar email.";
            // Para depuración: die("Error: " . $e->getMessage());
        }

        // Si la verificación de unicidad fue exitosa
        if (empty($errores)) {
            
            // 4. HASHEAR LA CONTRASEÑA (¡ESENCIAL para la seguridad!)
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // 5. PREPARAR SENTENCIA DE INSERCIÓN
            // Asegúrate de que el orden de las columnas coincida con el orden de las variables
            $sql_insert = "INSERT INTO cliente 
                           (nombre, apellido_paterno, apellido_materno, telefono, email, password, direccion) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_insert = $conn->prepare($sql_insert);
            
            // Enlazar parámetros: 7 strings (s x 7)
            $stmt_insert->bind_param("sssssss", 
                $nombres, 
                $apellido_paterno, 
                $apellido_materno, 
                $telefono, 
                $email, 
                $password_hash, 
                $direccion
            );
            
            // 6. EJECUTAR INSERCIÓN
            if ($stmt_insert->execute()) {
                
                // Registro exitoso, redirigir al login
                header("Location: login.html?registro=exitoso");
                exit();
                
            } else {
                // Error en la ejecución de la consulta (ej. tipo de dato incorrecto, longitud excedida)
                // Usamos el error de MySQLi para ayudar a depurar si es necesario
                die("Error al insertar en la base de datos: " . $stmt_insert->error); 
            }
            
            $stmt_insert->close();
        } 
    } 
    
    // 7. Si hay errores (de validación o de unicidad), mostrar los mensajes
    if (!empty($errores)) {
        // Cierra la conexión al final si hubo errores y no se ejecutó la redirección
        if (isset($conn)) $conn->close();
        
        // Muestra los errores al usuario (puedes mejorar esto con HTML más bonito)
        echo "<h2>Errores de Registro:</h2>";
        foreach ($errores as $error) {
            echo "<p style='color: red;'>&#x2717; $error</p>";
        }
        echo "<p><a href='registro.html'>Volver al registro</a></p>";
    }

} else {
    // Si alguien intenta acceder directamente al archivo sin enviar el formulario
    header("Location: registro.html");
    exit();
}
// Cierra la conexión al final si no hubo errores y el script terminó
if (isset($conn)) $conn->close();
?>