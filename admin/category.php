<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

require '../inc/db.php';

// Add category logic
if (isset($_POST['submit']) && !empty($_POST['category_name'])) {
    $category_name = trim($_POST['category_name']);
    if ($category_name !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO category (category_name) VALUES (:name)");
            $stmt->execute([':name' => $category_name]);
            // Optional: redirect to avoid resubmission
            header('Location: category.php?success=1');
            exit();
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Category name cannot be empty.</div>";
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM category");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard </title>
  <meta content="" name="description">
  <meta content="" name="keywords">



  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <?php include('inc/admin_header.php'); ?>

  <main id="main" class="main">


<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Add Category</h5>

      <form action="category.php" method="POST">
        <div class="row">
          <!-- Category Name -->
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="category_name" class="col-sm-4 col-form-label">Category Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="category_name" id="category_name" required>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
          <button type="submit" name="submit" class="btn btn-primary">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</section>


<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Datatables</h5>
                    <table class="table datatable">
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($categories): ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['id']); ?></td>
                                    <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                    <td><?php if (!empty($category['category_image'])): ?><img src="../<?php echo htmlspecialchars($category['category_image']); ?>" alt="Category Image" style="width:40px;height:40px;object-fit:cover;"/><?php endif; ?></td>
                                    <td class="action-icons">
                                    <i style="color: #3B71CA;" class="bx bx-edit icon-tooltip" title="Edit" onclick="window.location.href='inc/edit_category.php?id=<?php echo $category['id']; ?>'"></i>

<i style="color: #F44336;" class="bx bx-trash-alt icon-tooltip" title="Delete" onclick="deleteCategory(<?php echo $category['id']; ?>)"></i>


                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No categories found.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

function deleteCategory(id) {
    if (confirm("Are you sure you want to delete this category?")) {
        window.location.href = 'inc/delete_category.php?id=' + id;
    }
}

</script>
<style>
 .icon-tooltip {
    position: relative;
    display: inline-block;
    font-size: 28px; /* Icon size */
    cursor: pointer;
    margin: 0 8px; /* Spacing between icons */
    transition: transform 0.2s, color 0.2s; /* Hover effects */
}

.icon-tooltip:hover {
    transform: scale(1.2); /* Zoom effect on hover */
    opacity: 0.9;
}

/* Tooltip styling */
.icon-tooltip::after {
    content: attr(title); /* Get tooltip text from the title attribute */
    position: absolute;
    bottom: 120%; /* Position above the icon */
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 6px 10px;
    border-radius: 4px;
    white-space: nowrap;
    font-size: 12px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, transform 0.3s;
    z-index: 10;
}

/* Show tooltip on hover */
.icon-tooltip:hover::after {
    opacity: 1;
    visibility: visible;
    transform: translate(-50%, -5px); /* Slight upward movement */
}


</style> 


    

  </main><!-- End #main -->

  

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>