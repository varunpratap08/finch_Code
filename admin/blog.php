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

  <title>Dashboard Icon furniture</title>
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
  <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

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
      <h5 class="card-title">Add Blog</h5>

      <form action="inc/add_blog.php" method="POST" enctype="multipart/form-data">
        <div class="row">
          <!-- Blog Title -->
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="blog_title" class="col-sm-4 col-form-label">Blog Title</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="blog_title" id="blog_title" required>
              </div>
            </div>
          </div>

          <!-- Blog Author -->
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="author" class="col-sm-4 col-form-label">Author Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="author" id="author" required>
              </div>
            </div>
          </div>

          <!-- Blog Image -->
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="blog_image" class="col-sm-4 col-form-label">Blog Image</label>
              <div class="col-sm-8">
                <input type="file" class="form-control" name="blog_image" id="blog_image" accept="image/*" required>
              </div>
            </div>
          </div>

          <!-- Blog Content -->
          <div class="col-lg-12">
            <div class="mb-3 row">
              <label for="content" class="col-sm-2 col-form-label">Content</label>
              <div class="col-sm-10">
                <textarea class="form-control" name="content" id="content" rows="5" required></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
          <button type="submit" name="submit" class="btn btn-primary">Add Blog</button>
        </div>
      </form>
    </div>
  </div>
</section>
<script>
        CKEDITOR.replace('content');
        CKEDITOR.disableAutoInline = true;
CKEDITOR.config.versionCheck = false;
        
    </script>



<?php
require ('../inc/db.php');

// Fetch blogs from the database
$stmt = $pdo->prepare("SELECT * FROM blogs ORDER BY id DESC");
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Blog Posts</h5>
                    <table class="table datatable">
                        <tr>
                            <th>ID</th>
                            <th>Blog Title</th>
                            <th>Blog Image</th>
                            <th>Author</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($blogs): ?>
                            <?php foreach ($blogs as $blog): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($blog['id']); ?></td>
                                    <td><?php echo htmlspecialchars($blog['blog_title']); ?></td>
                                    <td>
                                        <img style="width:50px; border-radius: 50px;" src="../<?php echo htmlspecialchars($blog['blog_image']); ?>" alt="Blog Image">
                                    </td>
                                    <td><?php echo htmlspecialchars($blog['author']); ?></td>
                                    <td class="action-icons">
                                        <i style="color: #3B71CA;" class="bx bx-edit icon-tooltip" title="Edit" onclick="window.location.href='inc/edit_blog.php?id=<?php echo $blog['id']; ?>'"></i>

                                        <i style="color: #F44336;" class="bx bx-trash-alt icon-tooltip" title="Delete" onclick="deleteBlog(<?php echo $blog['id']; ?>)"></i>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No blogs found.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>

function deleteBlog(id) {
    if (confirm("Are you sure you want to delete this category?")) {
        window.location.href = 'inc/delete_blog.php?id=' + id;
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

