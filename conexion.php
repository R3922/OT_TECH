<?php
// Datos de conexión
$servername = "localhost";  // O "127.0.0.1"
$username   = "root";       // Usuario por defecto en XAMPP
$password   = "112233";           // Dejar en blanco si no tienes contraseña
$dbname     = "OTTECH"; // Reemplaza con el nombre real de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

echo "✅ Conexión exitosa a la base de datos '$dbname'";
?>
