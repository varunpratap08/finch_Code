<?php
require '../../inc/db.php'; // Database connection

// Fetch slider data based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM slider WHERE id = ?");
    $stmt->execute([$id]);
    $slider = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$slider) {
        die("Slider not found!");
    }
} else {
    die("Invalid request!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']) ?? null;
    $subtitle = trim($_POST['subtitle']) ?? null;
    $description = trim($_POST['description']) ?? null;
    $image = $_FILES['image'];

    if (!empty($title)) {
        // Check if a new image is uploaded
        if (!empty($image['name'])) {
            $uploadDir = "../../uploads/slider/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '_' . basename($image["name"]);
            $targetFile = $uploadDir . $imageName;
            $dbImagePath = "uploads/slider/" . $imageName;

            if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                // Delete the old image if it exists
                $oldImagePath = "../../" . $slider['image'];
                if (!empty($slider['image']) && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $stmt = $pdo->prepare("UPDATE slider SET title = :title, subtitle = :subtitle, description = :description, image = :image WHERE id = :id");
                $stmt->execute([
                    ':title' => $title,
                    ':subtitle' => $subtitle,
                    ':description' => $description,
                    ':image' => $dbImagePath,
                    ':id' => $id
                ]);
            } else {
                die("Failed to upload image.");
            }
        } else {
            // Update without changing the image
            $stmt = $pdo->prepare("UPDATE slider SET title = :title, subtitle = :subtitle, description = :description WHERE id = :id");
            $stmt->execute([
                ':title' => $title,
                ':subtitle' => $subtitle,
                ':description' => $description,
                ':id' => $id
            ]);
        }

        header("Location: ../slider.php?message=Slider updated successfully!");
        exit();
    } else {
        $error = "Title cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Slider</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #3B71CA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #2A5A9A;
        }

        img {
            display: block;
            max-width: 200px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Slider</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($slider['id']); ?>">

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($slider['title']); ?>" required>

            <label for="subtitle">Subtitle:</label>
            <input type="text" id="subtitle" name="subtitle" value="<?php echo htmlspecialchars($slider['subtitle']); ?>">

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($slider['description']); ?></textarea>

            <label for="image">Slider Image:</label>
            <input type="file" id="image" name="image">

            <?php if (!empty($slider['image'])) : ?>
                <img src="../../<?php echo htmlspecialchars($slider['image']); ?>" alt="Current Image">
            <?php endif; ?>

            <button type="submit" name="update">Update Slider</button>
        </form>
    </div>
</body>
</html>