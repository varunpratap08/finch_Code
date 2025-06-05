<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Finch Lock</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  

  <!-- Fonts -->
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

<body class="index-page">

 <?php include ('inc/header.php'); ?>

  <main class="main">
<style>
    /* About Section */
.about-section {
  padding: 90px 0; /* Adds spacing at the top and bottom */
  background-color: #f9f9f9; /* Light background for a clean look */
}

.about-section .container {
  display: flex;
  align-items: center;
}

.about-section img {
  width: 100%;
  max-width: 500px; /* Prevents image from being too large */
  border-radius: 10px; /* Adds a soft rounded border */
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow effect */
  height: -webkit-fill-available;
}

.about-section h1 {
  font-size: 36px;
  font-weight: bold;
  color: #333;
  margin-bottom: 15px;
}

.about-section p {
  font-size: 16px;
  color: #555;
  text-align: justify;
  line-height: 1.6;
  margin-bottom: 10px;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
  .about-section .row {
    flex-direction: column;
    text-align: center;
  }

  .about-section img {
    margin-bottom: 20px; /* Adds space below the image */
  }
}

</style>

   <section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <img src="assets/img/aboutp.png" alt="">
            </div>
            <div class="col-lg-6">
                <h1>About</h1>
                <p>Pali Industries is a high-end lock manufacturer, based in Aligarh, Uttar Pradesh, which specializes in OEM supplies to top brands. Production units are well expansives to handle processing of sheet metals right up to final assembly and packaging with an aim of quality output in every stage of production. Such quality culture of this company has impressed a strong reputation in the domestic and international markets through their ability to focus on customer satisfaction, integrity, and continuous improvement while offering durable and reliable locking solutions</p>
                <p>Building on success, Pali Industries launches its in-house brand - Finch Lock. Unlocking an advanced locking technology for the masses, Pali draws on years of experience to continue maintaining its standards while ideally combining precision engineering with innovative solutions. Finch Lock represents the next stage in delivering a superior product to meet a changing customer need.</p>
            </div>
        </div>
    </div>
   </section>


   <section class="about-section justify-content-justify">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h1>Our Mission</h1>
                <p>To establish Finch Lock as a trusted global brand by delivering innovative, secure, and high-quality locking solutions while maintaining excellence in manufacturing and customer satisfaction.</p>
                <h1>Our Vision</h1>
                <p>To leverage Pali Industries' extensive expertise and integrated production capabilities to provide durable and reliable locking solutions under the Finch Lock brand, meeting the diverse needs of customers and expanding into new markets with a strong focus on innovation and quality.</p>
            </div>
            <div class="col-lg-6">
                <img src="assets/img/mission.png" class="justify-content-justify" alt="">
            </div>
            
        </div>
    </div>
   </section>
    


<div class="container my-4">
  <div id="carouselExampleControls" class="carousel slide mx-auto" data-bs-ride="carousel" style="width: 85%; height: 300px; margin-left: auto; margin-right: auto;">
    <div class="carousel-inner" style="height: 100%;">
      <div class="carousel-item active">
        <img src="assets/img/mission.png" class="d-block w-100 h-100 object-fit-cover" alt="...">
      </div>
      <div class="carousel-item">
        <img src="assets/img/mission.png" class="d-block w-100 h-100 object-fit-cover" alt="...">
      </div>
      <div class="carousel-item">
        <img src="assets/img/mission.png" class="d-block w-100 h-100 object-fit-cover" alt="...">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>



    

  </main>

  <?php include ('inc/footer.php'); ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

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

</body>

</html>