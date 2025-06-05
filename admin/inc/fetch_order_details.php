<?php
require_once '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $orderId = intval($_GET['id']);

    // Fetch Order Details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $userId = $order['user_id'];

        // Fetch Billing Address
        $billingStmt = $pdo->prepare("SELECT * FROM user_billing_address WHERE user_id = ?");
        $billingStmt->execute([$userId]);
        $billingAddress = $billingStmt->fetch(PDO::FETCH_ASSOC);

        // Fetch Payment Method
        $paymentStmt = $pdo->prepare("SELECT * FROM payment_method WHERE user_id = ?");
        $paymentStmt->execute([$userId]);
        $paymentMethod = $paymentStmt->fetch(PDO::FETCH_ASSOC);

        // Send combined data as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'order' => $order,
            'billing_address' => $billingAddress,
            'payment_method' => $paymentMethod
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>
