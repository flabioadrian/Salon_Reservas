<?php
// procesar_login.php


session_start();


require 'conexion.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = trim($_POST['email']);
    $password = $_POST['password']; 



    if (empty($email) || empty($password))
         {
        header("Location: login.html?error=campos_vacios");
        exit();
    }

    try {

        $sql = "SELECT id_cliente, nombre, password  FROM cliente WHERE email = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result(); 


        if ($resultado->num_rows === 1) 
            {
            $usuario = $resultado->fetch_assoc();
            $hash_almacenado = $usuario['password']; // Obtener el hash guardado

            // 6. VERIFICAR LA CONTRASEÑA: Usar password_verify()
            if (password_verify($password, $hash_almacenado)) {
                
            
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['id_cliente'] = $usuario['id_cliente'];
                $_SESSION['nombre_usuario'] = $usuario['nombre'];
                header("Location: inicio.html"); 
                exit();

            } else 
            {
                header("Location: login.html?error=credenciales_invalidas");
                exit();
            }
        } else 
        {
            header("Location: login.html?error=credenciales_invalidas");
            exit();
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) 
    {
        header("Location: login.html?error=error_base_de_datos");
        exit();
    }

} 
else {
    header("Location: login.html");
    exit();
}
?>