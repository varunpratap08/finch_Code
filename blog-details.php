<?php
require 'inc/db.php'; // Database connection

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the blog details by ID
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        echo "<p>Blog not found.</p>";
        exit();
    }
} else {
    echo "<p>Invalid request.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Blog Details </title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <meta name="robots" content="noindex, nofollow">

 <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Append
  * Template URL: https://bootstrapmade.com/append-bootstrap-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="blog-details-page">

  <?php include ('inc/header.php'); ?>

  <main class="main">
      
      <div class="breadcrumb" style="
    display: flex;
    gap: 29px;
    text-decoration: none;
">
      <ul class="breadcrumb-menu" style=" display: flex;
    gap:3px;">
        <li><a href="index.html"><i class="far fa-home"></i> Home /</a></li>
        <li class="active"><?php echo htmlspecialchars($blog['blog_title']); ?></li>
      </ul>
    </div>
    
    <style>
         .breadcrumb {
    margin-top: 80px; /* Adjust as needed */
    padding: 10px 20px;
    border-radius: 5px;
   
}
.breadcrumb li{
    list-style: none;
    
    font-weight: 700;

}

    </style>

    

    <div class="container">
      <div class="row">

        <div class="col-lg-8">

          <!-- Blog Details Section -->
          <section id="blog-details" class="blog-details section">
            <div class="container">

              <article class="article">

                <div class="post-img">
                  <img src="<?php echo htmlspecialchars($blog['blog_image']); ?>" alt="" class="img-fluid">
                </div>

                <h2 class="title"><?php echo htmlspecialchars($blog['blog_title']); ?></h2>

               

                <div class="content">
                  <?php echo nl2br(strip_tags(html_entity_decode($blog['content']))); ?>

                </div><!-- End post content -->


              </article>

            </div>
          </section><!-- /Blog Details Section -->

          

        </div>
        
        <?php
// Fetch recent posts in random order
$stmt = $pdo->query("SELECT id, blog_title, blog_image, created_at FROM blogs ORDER BY RAND() LIMIT 5");
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

        <div class="col-lg-4 sidebar">

          <div class="widgets-container">

            <!-- Search Widget -->
            <div class="search-widget widget-item">

              

            </div><!--/Search Widget -->

           

            <!-- Recent Posts Widget -->
            <div class="recent-posts-widget widget-item">

              <h3 class="widget-title">Recent Posts</h3>
              
              <?php foreach ($blogs as $blog): ?>

              <div class="post-item">
                <img src="assets/img/blog/blog-recent-1.jpg" alt="" class="flex-shrink-0">
                <div>
                  <h4><a href="blog-details.php?id=<?php echo $blog['id']; ?>"><?php echo htmlspecialchars($blog['blog_title']); ?></a></h4>
                  <time datetime="<?php echo date("F d, Y", strtotime($blog['created_at'])); ?>"><?php echo date("F d, Y", strtotime($blog['created_at'])); ?>/time>
                </div>
              </div><!-- End recent post item-->
              <?php endforeach; ?>

              

            </div><!--/Recent Posts Widget -->

            

          </div>

        </div>

      </div>
    </div>

  </main>

<?php include ('inc/footer.php'); ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
    <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"928da57dca775478","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"version":"2025.1.0","token":"68c5ca450bae485a842ff76066d69420"}' crossorigin="anonymous"></script>
</body>

</html>