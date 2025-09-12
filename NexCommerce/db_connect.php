<?php
$servername = "localhost";
$username = "root"; // Padrão do XAMPP
$password = ""; // Padrão do XAMPP
$dbname = "nexcommerce_db";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>