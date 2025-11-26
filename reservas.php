<?php
// reservas.php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? 'crear';
    
    if ($accion == 'editar') {
        // Editar reserva existente
        $id_reserva = $_POST['id_reserva'];
        $nombre_sala = $_POST['nombre_sala'];
        $fecha = $_POST['fecha'];
        $precio = $_POST['precio'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        
        $sql = "UPDATE reservas SET 
                sala='$nombre_sala', 
                fecha='$fecha', 
                precio=$precio, 
                hora_inicio='$hora_inicio', 
                hora_fin='$hora_fin' 
                WHERE id=$id_reserva";
        
        if ($conn->query($sql) === TRUE) {
            echo "Reserva actualizada exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>