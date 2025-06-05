<?php
require '../../inc/db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']) ?? null;
    $subtitle = trim($_POST['subtitle']) ?? null;
    $description = trim($_POST['description']) ?? null;
    $image = $_FILES['image'];

    if (!empty($image['name'])) {
        $uploadDir = "../../uploads/slider/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . '_' . basename($image["name"]);
        $targetFile = $uploadDir . $imageName;
        $dbImagePath = "uploads/slider/" . $imageName;

        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            $stmt = $pdo->prepare("INSERT INTO slider (title, subtitle, description, image) VALUES (:title, :subtitle, :description, :image)");
            $stmt->execute([
                ':title' => $title,
                ':subtitle' => $subtitle,
                ':description' => $description,
                ':image' => $dbImagePath
            ]);

            header("Location: ../slider.php?message=Slider added successfully!");
            exit();
        } else {
            header("Location: ../slider.php?error=Failed to upload image!");
            exit();
        }
    } else {
        header("Location: ../slider.php?error=Image is required!");
        exit();
    }
}
?>
