<?php
header('Content-Type: application/json');
include 'db_connect.php'; // Pastikan untuk menghubungkan ke database Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $response = [
            'status' => 'success',
            'data' => [
                'id' => $stmt->insert_id,
                'username' => $username
            ]
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Registration failed'
        ];
    }

    echo json_encode($response);
    $stmt->close();
    $conn->close();
}
?>
