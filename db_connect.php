<?php
$servername = "localhost";
$username = "mobw7774_user_dhio";
$password = "Dhioandreas24.";
$dbname = "mobw7774_api_dhio";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
