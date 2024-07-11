<?php
header('Content-Type: application/json');
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $product_ids = isset($_POST['product_ids']) ? explode(',', $_POST['product_ids']) : [];

    error_log("Received user_id: " . $user_id);
    error_log("Received product_ids: " . json_encode($product_ids));

    if (empty($user_id) || empty($product_ids) || $product_ids[0] == "") {
        $response = [
            'status' => 'error',
            'message' => 'Invalid data'
        ];
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        $response = [
            'status' => 'error',
            'message' => 'Database error'
        ];
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $stmt->close();

        $success = true;
        foreach ($product_ids as $product_id) {
            $product_id = (int)$product_id;
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id) VALUES (?, ?)");
            if ($stmt === false) {
                error_log("Prepare failed: " . $conn->error);
                $success = false;
                break;
            }
            $stmt->bind_param("ii", $order_id, $product_id);
            if (!$stmt->execute()) {
                error_log("Failed to insert order item for product_id: " . $product_id . " Error: " . $stmt->error);
                $success = false;
                break;
            }
            $stmt->close();
        }

        if ($success) {
            $response = [
                'status' => 'success',
                'message' => 'Order created'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to create order items'
            ];
        }
    } else {
        error_log("Failed to create order: " . $stmt->error);
        $response = [
            'status' => 'error',
            'message' => 'Failed to create order'
        ];
        $stmt->close();
    }

    echo json_encode($response);
    $conn->close();
}
?>
