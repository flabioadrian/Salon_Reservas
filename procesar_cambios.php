<?php
// procesar_cambios_perfil.php

session_start();
require 'conexion.php'; 

// 1. Protección de Sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: login.html");
    exit;
}

$id_cliente = $_SESSION['id_cliente'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Recibir y Limpiar Datos
    $nombre = trim($_POST['nombre']);
    $apellido_paterno = trim($_POST['apellido_paterno']);
    $apellido_materno = trim($_POST['apellido_materno']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    
    // Validación básica
    if (empty($nombre) || empty($apellido_paterno)) {
        header("Location: Cambios.php?status=error&msg=campos_obligatorios");
        exit();
    }
    
    try {
        // 3. ACTUALIZAR LA BASE DE DATOS
        $sql = "UPDATE cliente 
                SET nombre = ?, 
                    apellido_paterno = ?, 
                    apellido_materno = ?, 
                    telefono = ?, 
                    direccion = ? 
                WHERE id_cliente = ?";
        
        $stmt = $conn->prepare($sql);
        

        $stmt->bind_param("sssssi", 
            $nombre, 
            $apellido_paterno, 
            $apellido_materno, 
            $telefono, 
            $direccion,
            $id_cliente
        );
        
        if ($stmt->execute()) {
            // Éxito: Redirige de vuelta a Cambios.php con mensaje de éxito
            header("Location: perfil.php?status=success");
        } else {
            // Error en la ejecución de la consulta
            header("Location: Cambios.php?status=error&msg=db_fail");
        }
        
        $stmt->close();

    } catch (Exception $e) {
        header("Location: Cambios.php?status=error&msg=exception");
    } finally {
        $conn->close();
    }

} else {
    // Si alguien accede directamente al script
    header("Location: Cambios.php");
    exit();
}
?>