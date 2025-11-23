<?php

session_start();
require 'conexion.php'; 

// 1. Protección: Si no está logueado, salir
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: login.html");
    exit;
}

$id_cliente = $_SESSION['id_cliente'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Recibir y validar datos
    $password_actual = $_POST['password_actual'];
    $password_nueva = $_POST['password_nueva'];
    $password_confirmar = $_POST['password_confirmar'];
    
    if ($password_nueva !== $password_confirmar) {
        header("Location: NewContra.php?error=no_coinciden");
        exit();
    }
  
    if (strlen($password_nueva) < 6) {
        header("Location: NewContra.php?error=corta");
        exit();
    }
    
    try {
        // 3. Obtener la contraseña HASH almacenada para verificar la actual
        $sql_select = "SELECT password FROM cliente WHERE id_cliente = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $id_cliente);
        $stmt_select->execute();
        $resultado = $stmt_select->get_result();

        if ($resultado->num_rows === 0) {
            header("Location: NewContra.php?error=usuario_no_encontrado");
            exit();
        }
        
        $fila = $resultado->fetch_assoc();
        $hash_almacenado = $fila['password'];
        $stmt_select->close();

        // 4. VERIFICAR CONTRASEÑA ACTUAL
        if (password_verify($password_actual, $hash_almacenado)) {
            
            // 5. Crear nuevo hash seguro para la nueva contraseña
            $nuevo_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
            
            // 6. ACTUALIZAR LA BASE DE DATOS con el nuevo hash
            $sql_update = "UPDATE cliente SET password = ? WHERE id_cliente = ?";
            $stmt_update = $conn->prepare($sql_update);
            
            // 'si' por string (hash) e integer (id)
            $stmt_update->bind_param("si", $nuevo_hash, $id_cliente);
            if ($stmt_update->execute()) {
           
                header("Location: NewContra.php?status=success"); 
            } else {
            
            else {
                // Error de la base de datos
                header("Location: NewContra.php?error=db_fail");
            }
            $stmt_update->close();

        } else {
            // Falla la verificación de la contraseña actual
            header("Location: NewContra.php?error=actual_incorrecta");
        }
        
    } catch (Exception $e) {
        header("Location: NewContra.php?error=exception");
    } finally {
        $conn->close();
    }

} else {

    header("Location: perfil.php");
    exit();
}
?>