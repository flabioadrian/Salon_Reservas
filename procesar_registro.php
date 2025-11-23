<?php
// procesar_registro.php


require 'conexion.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $nombres = trim($_POST['nombre']);
    $apellido_paterno = trim($_POST['apellido_paterno']);
    $apellido_materno = trim($_POST['apellido_materno']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; 
    $direccion = trim($_POST['direccion']);
    
 
    $errores = [];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
         {
        $errores[] = "El formato del email no es válido";}
    
    if (!preg_match('/^[0-9]{10}$/', $telefono)) 
        {
        $errores[] = "El teléfono debe tener 10 dígitos";
    }
    if (empty($password)) 
        {
        $errores[] = "La contraseña no puede estar vacía";
    }
    
   
    if (empty($errores)) {
        
        try 
        {
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
        
        }

        
        if (empty($errores)) {
            
            
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            

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
                
                die("Error al insertar en la base de datos: " . $stmt_insert->error); 
            }
            
            $stmt_insert->close();
        } 
    } 
    
    
    if (!empty($errores)) {
        
        if (isset($conn)) $conn->close();
        

        echo "<h2>Errores de Registro:</h2>";
        foreach ($errores as $error) {
            echo "<p style='color: red;'>&#x2717; $error</p>";
        }
        echo "<p><a href='registro.html'>Volver al registro</a></p>";
    }

} else {

    header("Location: registro.html");
    exit();
}

if (isset($conn)) $conn->close();
?>