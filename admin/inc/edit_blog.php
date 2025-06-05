<?php
require '../../inc/db.php'; // Database connection

// Fetch blog data based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        die("Blog not found!");
    }
} else {
    die("Invalid request!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $blog_title = trim($_POST['blog_title']);
    $author = trim($_POST['author']);
    $content = trim($_POST['content']);
    $image = $_FILES['blog_image'];

    if (!empty($blog_title) && !empty($author) && !empty($content)) {
        // Check if a new image is uploaded
        if (!empty($image['name'])) {
            $uploadDir = "../../uploads/blogs/"; 
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '_' . basename($image["name"]);
            $targetFile = $uploadDir . $imageName;
            $dbImagePath = "uploads/blogs/" . $imageName;

            // Upload the image
            if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                // Delete the old image if it exists
                $oldImagePath = "../../" . $blog['blog_image'];
                if (!empty($blog['blog_image']) && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Update with new image
                $stmt = $pdo->prepare("UPDATE blogs SET blog_title = :blog_title, author = :author, content = :content, blog_image = :blog_image WHERE id = :id");
                $stmt->execute([
                    ':blog_title' => $blog_title,
                    ':author' => $author,
                    ':content' => $content,
                    ':blog_image' => $dbImagePath,
                    ':id' => $id
                ]);
            } else {
                die("Failed to upload image.");
            }
        } else {
            // Update without changing the image
            $stmt = $pdo->prepare("UPDATE blogs SET blog_title = :blog_title, author = :author, content = :content WHERE id = :id");
            $stmt->execute([
                ':blog_title' => $blog_title,
                ':author' => $author,
                ':content' => $content,
                ':id' => $id
            ]);
        }

        header("Location: ../blog.php?message=Blog updated successfully!");
        exit();
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Blog</title>
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
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
        <h2>Edit Blog</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($blog['id']); ?>">

            <label for="blog_title">Blog Title:</label>
            <input type="text" id="blog_title" name="blog_title" value="<?php echo htmlspecialchars($blog['blog_title']); ?>" required>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($blog['author']); ?>" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($blog['content']); ?></textarea>

            <label for="blog_image">Blog Image:</label>
            <input type="file" id="blog_image" name="blog_image">

            <!-- Show current image -->
            <?php if (!empty($blog['blog_image'])) : ?>
                <img src="../../<?php echo htmlspecialchars($blog['blog_image']); ?>" alt="Current Image">
            <?php endif; ?>

            <button type="submit" name="update">Update Blog</button>
        </form>
    </div>
    <script>
        CKEDITOR.replace('content');
        CKEDITOR.disableAutoInline = true;
CKEDITOR.config.versionCheck = false;
    </script>
</body>
</html>
