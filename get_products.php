<?php
header('Content-Type: application/json');
include 'db_connect.php'; // Pastikan untuk menghubungkan ke database Anda

$query = "SELECT id, name, price FROM products";
$result = $conn->query($query);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$response = [
    'status' => 'success',
    'data' => $products
];

echo json_encode($response);
$conn->close();
?>
