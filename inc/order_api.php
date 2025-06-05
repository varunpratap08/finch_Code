<?php
require_once 'db.php'; // Ensure this file contains the PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve and sanitize form data
        $product_id = intval($_POST['product_id'] ?? 0);
        $customer_name = trim($_POST['customer_name'] ?? '');
        $customer_email = trim($_POST['customer_email'] ?? '');
        $customer_phone = trim($_POST['customer_phone'] ?? '');
        $customer_address = trim($_POST['customer_address'] ?? '');

        // Validate required fields
        if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_address)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        // Fetch product data (including pricing JSON)
        $stmt = $pdo->prepare("SELECT pricing FROM products WHERE id = :product_id");
        $stmt->execute([':product_id' => $product_id]);
        $product_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product_data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product selected.']);
            exit;
        }

        // Decode pricing JSON
        $pricing_data = json_decode($product_data['pricing'], true);

        if (!is_array($pricing_data) || empty($pricing_data)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or missing pricing data.']);
            exit;
        }

        // Retrieve selected options
        $sizes = $_POST['sizes'] ?? [];
        $finishes = $_POST['finishes'] ?? [];
        $quantities = $_POST['quantities'] ?? [];

        $order_items = [];
        $total_price = 0;
        $valid_order = false;

        // Debug information
        error_log('Sizes: ' . print_r($sizes, true));
        error_log('Finishes: ' . print_r($finishes, true));
        error_log('Quantities: ' . print_r($quantities, true));
        error_log('Pricing data: ' . print_r($pricing_data, true));
        
        for ($i = 0; $i < count($quantities); $i++) {
            $size = isset($sizes[$i]) && !empty(trim($sizes[$i])) ? htmlspecialchars(trim($sizes[$i])) : 'NA';
            // Get the raw finish code (sn, bk, etc.)
            $finish_code = isset($finishes[$i]) ? strtolower(trim(htmlspecialchars($finishes[$i]))) : '';
            
            // Map finish codes to display names for admin panel
            $finish_names = [
                'sn' => 'Satin Nickel',
                'bk' => 'Black',
                'an' => 'Antique Nickel',
                'gd' => 'Gold',
                'rg' => 'Rose Gold',
                'ch' => 'Chrome',
                'gl' => 'Glossy'
            ];
            
            // Use the display name if available, otherwise use the code
            $finish_display = isset($finish_names[$finish_code]) ? $finish_names[$finish_code] : $finish_code;
            $finish = $finish_code; // Keep the original code for price lookup
            $quantity = intval($quantities[$i]);
            
            error_log("Processing item $i: Size=$size, Finish=$finish, Quantity=$quantity");

            if (empty($finish) || $quantity <= 0) {
                error_log("Skipping item $i: Empty finish or invalid quantity");
                continue;
            }

            $price_per_unit = 0;
            foreach ($pricing_data as $pricing) {
                error_log("Checking pricing entry: " . print_r($pricing, true));
                if ($pricing['size'] === $size) {
                    $price_key = $finish . '_price';
                    if (isset($pricing[$price_key]) && floatval($pricing[$price_key]) > 0) {
                        $price_per_unit = floatval($pricing[$price_key]);
                        error_log("Found price for $size/$finish: $price_per_unit");
                        break;
                    }
                }
            }
            
            // If no price found, set a default price for testing purposes
            if ($price_per_unit <= 0) {
                $price_per_unit = 100; // Default price for testing
                error_log("No price found for $size/$finish, using default price: $price_per_unit");
            }

            $subtotal = $price_per_unit * $quantity;
            $total_price += $subtotal;
            $valid_order = true;

            $order_items[] = [
                'size' => $size,
                'finish' => $finish_display, // Use the display name for admin panel
                'finish_code' => $finish, // Keep the code for reference
                'quantity' => $quantity,
                'price_per_unit' => $price_per_unit,
                'subtotal' => $subtotal
            ];
        }

        if (!$valid_order) {
            echo json_encode(['status' => 'error', 'message' => 'No valid items selected for purchase.']);
            exit;
        }

        // Encode order details in JSON format
        $order_json = json_encode($order_items);

        // Insert data into orders table
        $stmt = $pdo->prepare("INSERT INTO orders 
            (product_id, customer_name, customer_email, customer_phone, customer_address, order_details, total_price) 
            VALUES (:product_id, :customer_name, :customer_email, :customer_phone, :customer_address, :order_details, :total_price)");

        $stmt->execute([
            ':product_id' => $product_id,
            ':customer_name' => $customer_name,
            ':customer_email' => $customer_email,
            ':customer_phone' => $customer_phone,
            ':customer_address' => $customer_address,
            ':order_details' => $order_json,
            ':total_price' => $total_price
        ]);

        header("Location: ../success.php");
        exit();

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}