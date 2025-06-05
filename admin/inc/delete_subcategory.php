<?php
require '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    try {
        // Prepare delete statement
        $stmt = $pdo->prepare("DELETE FROM subcategory WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            header("Location: ../sub-category.php?message=Sub Category deleted successfully!");
        } else {
            header("Location: ../sub-category.php?error=Category not found!");
        }
        exit();
    } catch (Exception $e) {
        header("Location: ../sub-category.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../sub-category.php?error=Invalid request!");
    exit();
}
?>
