<?php
require '../../inc/db.php'; // Include your database connection file

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    // Fetch subcategories based on category selection
    $stmt = $pdo->prepare("SELECT id, subcategory_name FROM subcategory WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subcategories);
}
?>
