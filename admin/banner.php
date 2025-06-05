


<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
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

    
  <?php
require '../inc/db.php';

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video = $_FILES['video'];

    $upload_dir = "../uploads/video/"; // Directory to store the file
    $db_path = "uploads/video/";       // Path for the database

    $target_file = $upload_dir . basename($video['name']);
    $db_file = $db_path . basename($video['name']); // Store relative path in DB

    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['mp4', 'webm', 'ogg', 'avi', 'mov'];

    // Check if the file is a valid video
    if (!in_array($videoFileType, $allowed_types)) {
        die("Only MP4, WebM, OGG, AVI, and MOV files are allowed.");
    }

    // Check file size (limit: 50MB)
    if ($video['size'] > 50 * 1024 * 1024) {
        die("File is too large. Maximum allowed size is 50MB.");
    }

    // Move uploaded file to the target directory
    if (move_uploaded_file($video["tmp_name"], $target_file)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO banner (title, description, video) VALUES (:title, :description, :video)");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':video' => $db_file // Store relative path in DB
            ]);
            echo "Banner added successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Fetch banners from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM banner");
    $stmt->execute();
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Add Category</h5>

      <form action="banner.php" method="POST" enctype="multipart/form-data">
        <div class="row">
          <!-- Category Name -->
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="title" class="col-sm-4 col-form-label">Title</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="title" id="title" required>
              </div>
            </div>
          </div>

          

          <!-- Category Image -->
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="video" class="col-sm-4 col-form-label" >Background Video</label>
              <div class="col-sm-8">
                <input type="file" class="form-control" name="video" id="video" accept="video/*" required>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="description" class="col-sm-4 col-form-label">Description</label>
              <div class="col-sm-8">
                <textarea type="text" class="form-control" name="description" id="description" required row="4"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
          <button type="submit" name="submit" class="btn btn-primary">Add Banner</button>
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
                            <th>Title</th>
                            <th>Description</th>
                            <th>Video</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($banners): ?>
                            <?php foreach ($banners as $banner): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($banner['id']); ?></td>
                                    <td><?php echo htmlspecialchars($banner['title']); ?></td>
                                    <td><?php echo htmlspecialchars($banner['description']); ?></td>
                                    <td>
    <video width="100" height="50" style="border-radius: 10px;" controls>
        <source src="../<?php echo htmlspecialchars($banner['video']); ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</td>

                                    <td class="action-icons">
                                    <i style="color: #3B71CA;" class="bx bx-edit icon-tooltip" title="Edit" onclick="window.location.href='inc/edit_banner.php?id=<?php echo $banner['id']; ?>'"></i>

<i style="color: #F44336;" class="bx bx-trash-alt icon-tooltip" title="Delete" onclick="deleteBanner(<?php echo $banner['id']; ?>)"></i>


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

function deleteBanner(id) {
    if (confirm("Are you sure you want to delete this category?")) {
        window.location.href = 'inc/delete_banner.php?id=' + id;
    }
}

</script>


    

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