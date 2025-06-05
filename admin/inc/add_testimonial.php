<?php
require '../../inc/db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $author_name = trim($_POST['author_name']);
    $author_role = trim($_POST['author_role']);
    $testimonial_text = trim($_POST['testimonial_text']);
    $rating = (int)$_POST['rating'];
    $author_image = $_FILES['author_image'];

    if (!empty($author_name) && !empty($author_role) && !empty($testimonial_text) && !empty($author_image['name']) && $rating >= 1 && $rating <= 5) {
        $uploadDir = "../../uploads/testimonials/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . '_' . basename($author_image["name"]);
        $targetFile = $uploadDir . $imageName;
        $dbImagePath = "uploads/testimonials/" . $imageName;

        if (move_uploaded_file($author_image["tmp_name"], $targetFile)) {
            $stmt = $pdo->prepare("INSERT INTO testimonial (author_name, author_role, testimonial_text, rating, author_image) 
                                   VALUES (:author_name, :author_role, :testimonial_text, :rating, :author_image)");
            $stmt->execute([
                ':author_name' => $author_name,
                ':author_role' => $author_role,
                ':testimonial_text' => $testimonial_text,
                ':rating' => $rating,
                ':author_image' => $dbImagePath
            ]);

            header("Location: ../testimonials.php?message=Testimonial added successfully!");
            exit();
        } else {
            header("Location: ../testimonials.php?error=Failed to upload image!");
            exit();
        }
    } else {
        header("Location: ../testimonials.php?error=All fields are required or rating is invalid!");
        exit();
    }
}
?>
