<?php
// procesar_login.php

// 1. INICIAR SESIÓN: Es fundamental para guardar el estado de login del usuario.
session_start();

// 2. INCLUIR LA CONEXIÓN A LA BASE DE DATOS (Usando tu objeto $conn de MySQLi)
require 'conexion.php'; 

// 3. Verificar que el formulario se envió por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener y limpiar los datos del formulario
    $email = trim($_POST['email']);
    $password = $_POST['password']; // No limpiar la contraseña antes de verificar

    // Validar que los campos no estén vacíos
    if (empty($email) || empty($password)) {
        header("Location: login.html?error=campos_vacios");
        exit();
    }

    try {
        // 4. PREPARAR SENTENCIA SQL: Buscar el usuario por correo electrónico
        // Solo necesitamos el ID, el nombre y el HASH de la contraseña (columna 'password')
        $sql = "SELECT id_cliente, nombre, password  FROM cliente WHERE email = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result(); // Obtiene el resultado

        // 5. VERIFICAR SI EL USUARIO EXISTE
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            $hash_almacenado = $usuario['password']; // Obtener el hash guardado

            // 6. VERIFICAR LA CONTRASEÑA: Usar password_verify()
            if (password_verify($password, $hash_almacenado)) {
                
                // ¡LOGIN EXITOSO!
                
                // 7. CREAR VARIABLES DE SESIÓN: Guardar datos del usuario
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['id_cliente'] = $usuario['id_cliente'];
                $_SESSION['nombre_usuario'] = $usuario['nombre'];
                header("Location: index.html"); 
                exit();

            } else {
                // Contraseña incorrecta
                header("Location: login.html?error=credenciales_invalidas");
                exit();
            }
        } else {
            // Usuario no encontrado
            header("Location: login.html?error=credenciales_invalidas");
            exit();
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        // Error de base de datos o ejecución
        header("Location: login.html?error=error_base_de_datos");
        exit();
    }

} else {
    // Si se accede directamente, redirigir al login
    header("Location: login.html");
    exit();
}
?>