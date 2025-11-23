<?php

$host = "localhost";
$user = "root";
$pass = ""; 
$bd = "salones"; 

$conn = new mysqli($host, $user, $pass, $bd);


if ($conn->connect_error) {

    die("Conexión fallida: " . $conn->connect_error);
}

?>