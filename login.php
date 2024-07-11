<?php
header('Content-Type: application/json');
include 'db_connect.php'; // Pastikan untuk menghubungkan ke database Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $db_username, $db_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $db_password)) {
            $response = [
                'status' => 'success',
                'data' => [
                    'id' => $id,
                    'username' => $db_username
                ]
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid password'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'User not found'
        ];
    }

    echo json_encode($response);
    $stmt->close();
    $conn->close();
}
?>
