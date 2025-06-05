<?php
require '../../inc/db.php'; // Database connection

// Fetch banner data based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM banner WHERE id = ?");
    $stmt->execute([$id]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$banner) {
        die("Banner not found!");
    }
} else {
    die("Invalid request!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $video = trim($_POST['video']);

    if (!empty($title) && !empty($description) && !empty($video)) {
        // Update banner details
        $stmt = $pdo->prepare("UPDATE banner SET title = :title, description = :description, video = :video WHERE id = :id");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':video' => $video,
            ':id' => $id
        ]);

        header("Location: ../banner.php?message=Banner updated successfully!");
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
    <title>Edit Banner</title>
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
        textarea {
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

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Banner</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($banner['id']); ?>">

            <label for="title">Banner Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($banner['title']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($banner['description']); ?></textarea>

            <label for="video">Video URL:</label>
            <input type="text" id="video" name="video" value="<?php echo htmlspecialchars($banner['video']); ?>" required>

            <button type="submit" name="update">Update Banner</button>
        </form>
    </div>
</body>
</html>