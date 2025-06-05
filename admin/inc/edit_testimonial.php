<?php
require '../../inc/db.php'; // Database connection

// Fetch testimonial data based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM testimonial WHERE id = ?");
    $stmt->execute([$id]);
    $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$testimonial) {
        die("Testimonial not found!");
    }
} else {
    die("Invalid request!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $updateFields = [];
    $updateValues = [];

    if (!empty(trim($_POST['author_name'])) && $_POST['author_name'] !== $testimonial['author_name']) {
        $updateFields[] = "author_name = :author_name";
        $updateValues[':author_name'] = trim($_POST['author_name']);
    }

    if (!empty(trim($_POST['author_role'])) && $_POST['author_role'] !== $testimonial['author_role']) {
        $updateFields[] = "author_role = :author_role";
        $updateValues[':author_role'] = trim($_POST['author_role']);
    }

    if (!empty(trim($_POST['rating'])) && $_POST['rating'] !== $testimonial['rating']) {
        $updateFields[] = "rating = :rating";
        $updateValues[':rating'] = trim($_POST['rating']);
    }

    if (!empty(trim($_POST['testimonial_text'])) && $_POST['testimonial_text'] !== $testimonial['testimonial_text']) {
        $updateFields[] = "testimonial_text = :testimonial_text";
        $updateValues[':testimonial_text'] = trim($_POST['testimonial_text']);
    }

    // Handle image upload
    if (!empty($_FILES['author_image']['name'])) {
        $uploadDir = "../../uploads/testimonial/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . '_' . basename($_FILES["author_image"]["name"]);
        $targetFile = $uploadDir . $imageName;
        $dbImagePath = "uploads/testimonial/" . $imageName;

        if (move_uploaded_file($_FILES["author_image"]["tmp_name"], $targetFile)) {
            if (!empty($testimonial['author_image']) && file_exists("../../" . $testimonial['author_image'])) {
                unlink("../../" . $testimonial['author_image']);
            }
            $updateFields[] = "author_image = :author_image";
            $updateValues[':author_image'] = $dbImagePath;
        } else {
            die("Failed to upload image.");
        }
    }

    if (!empty($updateFields)) {
        $sql = "UPDATE testimonial SET " . implode(", ", $updateFields) . " WHERE id = :id";
        $updateValues[':id'] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($updateValues);
    }

    header("Location: ../testimonials.php?message=Testimonial updated successfully!");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Testimonial</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .container {
            max-width: 600px;
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
        input[type="number"],
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
        <h2>Edit Testimonial</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($testimonial['id']); ?>">

            <label for="author_name">Author Name:</label>
            <input type="text" id="author_name" name="author_name" value="<?php echo htmlspecialchars($testimonial['author_name']); ?>" required>

            <label for="author_role">Author role:</label>
            <input type="text" id="author_role" name="author_role" value="<?php echo htmlspecialchars($testimonial['author_role']); ?>" required>

            <label for="rating">Rating:</label>
            <input type="number" id="rating" name="rating" min="1" max="5" value="<?php echo htmlspecialchars($testimonial['rating']); ?>" required>
            

            <label for="testimonial_text">Testimonial Text:</label>
            <textarea id="testimonial_text" name="testimonial_text" rows="4" required><?php echo htmlspecialchars($testimonial['testimonial_text']); ?></textarea>

            <label for="author_image">Author Image:</label>
            <input type="file" id="author_image" name="author_image">

            <?php if (!empty($testimonial['author_image'])) : ?>
                <img src="../../<?php echo htmlspecialchars($testimonial['author_image']); ?>" alt="Author Image">
            <?php endif; ?>

            <button type="submit" name="update">Update Testimonial</button>
        </form>
    </div>
</body>
</html>
