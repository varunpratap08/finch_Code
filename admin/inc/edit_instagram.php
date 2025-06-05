<?php
require '../../inc/db.php'; // Database connection

// Fetch Instagram data based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM instagram WHERE id = ?");
    $stmt->execute([$id]);
    $instagram = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$instagram) {
        die("Instagram post not found!");
    }
} else {
    die("Invalid request!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $link = trim($_POST['link']);
    $image = $_FILES['image'];

    if (!empty($link)) {
        // Check if a new image is uploaded
        if (!empty($image['name'])) {
            $uploadDir = "../../upload/insta/"; // Upload directory
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
            }

            $imageName = time() . '_' . basename($image["name"]);
            $targetFile = $uploadDir . $imageName;              // Full path for the file upload
            $dbImagePath = "upload/insta/" . $imageName;        // Path to store in DB

            // Upload the image
            if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                // Delete the old image if it exists
                $oldImagePath = "../../" . $instagram['image_path'];
                if (!empty($instagram['image_path']) && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Update with new image
                $stmt = $pdo->prepare("UPDATE instagram SET instagram_link = :link, image_path = :image WHERE id = :id");
                $stmt->execute([
                    ':link' => $link,
                    ':image' => $dbImagePath,
                    ':id' => $id
                ]);
            } else {
                die("Failed to upload image.");
            }
        } else {
            // Update without changing the image
            $stmt = $pdo->prepare("UPDATE instagram SET instagram_link = :link WHERE id = :id");
            $stmt->execute([
                ':link' => $link,
                ':id' => $id
            ]);
        }

        header("Location: ../instagram.php?message=Instagram post updated successfully!");
        exit();
    } else {
        $error = "Instagram link cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Instagram Post</title>
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
        input[type="text"], input[type="file"] {
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
            max-width: 150px;
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
        <h2>Edit Instagram Post</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($instagram['id']); ?>">

            <label for="link">Instagram Link:</label>
            <input type="text" id="link" name="link" value="<?php echo htmlspecialchars($instagram['instagram_link']); ?>" required>

            <label for="image">Instagram Image:</label>
            <input type="file" id="image" name="image">

            <!-- Show current image -->
            <?php if (!empty($instagram['image_path'])) : ?>
                <img src="../../<?php echo htmlspecialchars($instagram['image_path']); ?>" alt="Current Image">
            <?php endif; ?>

            <button type="submit" name="update">Update Post</button>
        </form>
    </div>
</body>
</html>
