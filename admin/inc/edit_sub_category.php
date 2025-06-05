<?php
require '../../inc/db.php'; // Database connection

// Fetch categories for dropdown
$stmt = $pdo->prepare("SELECT * FROM category ORDER BY category_name ASC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch subcategory data based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM subcategory WHERE id = ?");
    $stmt->execute([$id]);
    $subcategory = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$subcategory) {
        die("Subcategory not found!");
    }
} else {
    die("Invalid request!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $category_id = $_POST['category_id']; // Selected category

    if (!empty($name) && !empty($category_id)) {
        // Update subcategory details
        $stmt = $pdo->prepare("UPDATE subcategory SET subcategory_name = :name, category_id = :category_id WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':category_id' => $category_id,
            ':id' => $id
        ]);

        header("Location: ../sub-category.php?message=Subcategory updated successfully!");
        exit();
    } else {
        $error = "Subcategory name and category must be selected!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Subcategory</title>
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
        select {
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
        <h2>Edit Subcategory</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($subcategory['id']); ?>">

            <label for="name">Subcategory Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($subcategory['subcategory_name']); ?>" required>

            <label for="category">Parent Category:</label>
            <select id="category" name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" 
                        <?php echo ($subcategory['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="update">Update Subcategory</button>
        </form>
    </div>
</body>
</html>
