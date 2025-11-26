<?php
// salas.php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? 'crear';
    
    if ($accion == 'crear') {
        // Crear nueva sala
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $capacidad = $_POST['capacidad'];
        $tipo = $_POST['tipo'];
        $servicios = $_POST['servicios'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        
        $sql = "INSERT INTO salas (nombre, descripcion, capacidad, tipo, servicios, hora_inicio, hora_fin) 
                VALUES ('$nombre', '$descripcion', $capacidad, '$tipo', '$servicios', '$hora_inicio', '$hora_fin')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Sala creada exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($accion == 'editar') {
        // Editar sala existente
        $id_sala = $_POST['id_sala'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $capacidad = $_POST['capacidad'];
        $tipo = $_POST['tipo'];
        $servicios = $_POST['servicios'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        
        $sql = "UPDATE salas SET 
                nombre='$nombre', 
                descripcion='$descripcion', 
                capacidad=$capacidad, 
                tipo='$tipo', 
                servicios='$servicios', 
                hora_inicio='$hora_inicio', 
                hora_fin='$hora_fin' 
                WHERE id=$id_sala";
        
        if ($conn->query($sql) === TRUE) {
            echo "Sala actualizada exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>