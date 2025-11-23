<?php
// obtener_nombre.php

// Inicia la sesión para acceder a $_SESSION
session_start();

// Establece el encabezado para que el navegador sepa que es JSON
header('Content-Type: application/json');

// Comprueba si el usuario está logueado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    // Si está logueado, devuelve el nombre
    echo json_encode([
        'success' => true,
        'nombre' => $_SESSION['nombre_usuario']
    ]);
} else {
    // Si NO está logueado, devuelve un error.
    // **IMPORTANTE:** Aquí se podría redirigir a login.html si el cliente usa PHP,
    // pero como lo llamamos con AJAX, solo devolvemos el error.
    echo json_encode([
        'success' => false,
        'mensaje' => 'No hay sesión activa'
    ]);
}
exit;
?>