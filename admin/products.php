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
  <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>


  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    td{
      font-size: 12px;
    }
    th{
      font-size: 12px;
    }
  </style>
  

</head>

<body>




  <?php include('inc/admin_header.php'); ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Products</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
   


   <?php
require '../inc/db.php'; // Include your database connection file

// Fetch categories
$categories = $pdo->query("SELECT id, category_name FROM category")->fetchAll(PDO::FETCH_ASSOC);

// Fetch subcategories based on category selection
$subcategories = [];
if (isset($_POST['category']) && !empty($_POST['category'])) {
    $stmt = $pdo->prepare("SELECT id, subcategory_name FROM subcategory WHERE category_id = ?");
    // $stmt->execute([$_POST['category']]);
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category']; // This is the category id from the select
    $sub_category_id = $_POST['sub_category']; // This is the subcategory id from the select
    $description = $_POST['description'];
    $sizes = $_POST['sizes'];
    $sn_prices = $_POST['sn_price'];
    $bk_prices = $_POST['bk_price'];
    $an_prices = $_POST['an_price'];
    $gd_prices = $_POST['gd_price'];
    $rg_prices = $_POST['rg_price'];
    $ch_prices = $_POST['ch_price'];
    $gl_prices = $_POST['gl_price'];
    $pricing_data = [];
    foreach ($sizes as $key => $size) {
        $pricing_data[] = [
            'size' => $size,
            'sn_price' => $sn_prices[$key],
            'bk_price' => $bk_prices[$key],
            'an_price' => $an_prices[$key],
            'gd_price' => $gd_prices[$key],
            'rg_price' => $rg_prices[$key],
            'ch_price' => $ch_prices[$key],
            'gl_price' => $gl_prices[$key]
        ];
    }
    $pricing_json = json_encode($pricing_data);
    $target_dir = "../uploads/product/";
    $image_name = time() . "_" . basename($_FILES["product_image"]["name"]);
    $target_file = $target_dir . $image_name;
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        $stored_path = "uploads/product/" . $image_name;
    } else {
        $errors['product_image'] = "Failed to upload image.";
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO products (product_name, category, sub_category, description, pricing, product_image) VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$product_name, $category_id, $sub_category_id, $description, $pricing_json, $stored_path]);
        if ($success) {
            echo "<div style='color: green; font-weight: bold;'>Product added successfully!</div>";
        } else {
            echo "<div style='color: red; font-weight: bold;'>Failed to add product. Please try again.</div>";
        }
    } catch (PDOException $e) {
        echo "<div style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Add Product</h5>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="row">
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="product_name" class="col-sm-4 col-form-label">Product Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="product_name" required>
              </div>
            </div>
          </div>

          <!-- Category -->
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="category" class="col-sm-4 col-form-label">Category</label>
              <div class="col-sm-8">
                <select name="category" class="form-select" id="category" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
        <?php endforeach; ?>
    </select>
              </div>
            </div>
          </div>

          <!-- Sub-Category -->
         <div class="col-lg-6">
    <div class="mb-3 row">
        <label for="sub_category" class="col-sm-4 col-form-label">Sub-Category</label>
        <div class="col-sm-8">
            <select name="sub_category" class="form-select" id="sub_category">
        <option value="">Select Subcategory</option>
    </select>
        </div>
    </div>
</div>


        <div class="mb-3">
          <label for="description" class="form-label">Product Description</label>
          <textarea class="form-control" name="description" id="description"></textarea>
        </div>

        <div class="mb-3">
          <label for="product_image" class="form-label">Product Image</label>
          <input type="file" class="form-control" name="product_image" accept="image/*" required>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered" id="priceTable">
            <thead>
              <tr>
                <th>Size</th>
                <th>SN</th>
                <th>BK</th>
                <th>AN</th>
                <th>GD</th>
                <th>RG</th>
                <th>CH</th>
                <th>GL</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" class="form-control" name="sizes[]"></td>
                <td><input type="text" class="form-control" name="sn_price[]"></td>
                <td><input type="text" class="form-control" name="bk_price[]"></td>
                <td><input type="text" class="form-control" name="an_price[]"></td>
                <td><input type="text" class="form-control" name="gd_price[]"></td>
                <td><input type="text" class="form-control" name="rg_price[]"></td>
                <td><input type="text" class="form-control" name="ch_price[]"></td>
                <td><input type="text" class="form-control" name="gl_price[]"></td>
                <td><button type="button" class="btn btn-danger removeRow">X</button></td>
              </tr>
            </tbody>
          </table>
          <button type="button" class="btn btn-success" id="addRow">+ Add Row</button>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</section>




<script>
document.getElementById('addRow').addEventListener('click', function () {
    let table = document.getElementById('priceTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow();
    newRow.innerHTML = `<td><input type='text' class='form-control' name='sizes[]' required></td>
                         <td><input type='text' class='form-control' name='sn_price[]'></td>
                         <td><input type='text' class='form-control' name='bk_price[]'></td>
                         <td><input type='text' class='form-control' name='an_price[]'></td>
                         <td><input type='text' class='form-control' name='gd_price[]'></td>
                         <td><input type='text' class='form-control' name='rg_price[]'></td>
                         <td><input type='text' class='form-control' name='ch_price[]'></td>
                         <td><input type='text' class='form-control' name='gl_price[]'></td>
                         <td><button type='button' class='btn btn-danger removeRow'>X</button></td>`;
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeRow')) {
        e.target.closest('tr').remove();
    }
});


document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("category").addEventListener("change", function () {
        let categoryId = this.value;
        let subCategorySelect = document.getElementById("sub_category");

        // Clear previous subcategories
        subCategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

        if (categoryId) {
            let formData = new FormData();
            formData.append("category_id", categoryId);

            fetch("inc/fetch_subcategories.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                data.forEach(subcategory => {
                    let option = document.createElement("option");
                    option.value = subcategory.id;
                    option.textContent = subcategory.subcategory_name;
                    subCategorySelect.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching subcategories:", error));
        }
    });
});

</script>





<!-- Include CKEditor -->

<?php

// Fetch products from the database
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>




<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card table-responsive">
                
                <div class="card-body">
                <div class="text-right">
    <button class="btn add-product-btn">Add Products</button>
</div>

                    <h5 class="card-title">Datatables</h5>
                    <table class="table datatable ">
                        <tr>
                            <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Description</th>
                <th>Pricing</th>
                <th>Image</th>
                        </tr>
                        <?php if ($products): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                   <td><?= htmlspecialchars($product['id']) ?></td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td><?= htmlspecialchars($product['sub_category'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                         <td>
                            <?php 
                                $pricing = json_decode($product['pricing'], true);
                                if ($pricing) {
                                    foreach ($pricing as $price) {
                                        echo "<strong>Size:</strong> " . htmlspecialchars($price['size']) . "<br>";
                                        echo "<strong>SN Price:</strong> $" . htmlspecialchars($price['sn_price']) . "<br>";
                                    }
                                } else {
                                    echo "N/A";
                                }
                            ?>
                        </td>
                         <td><img style="width: 20px; height: 20px;" src="../<?= htmlspecialchars($product['product_image']) ?>" alt="Product Image"></td>
                                    <td class="action-icons">
                                    <i style="color: #3B71CA;" class="bx bx-edit icon-tooltip" title="Edit" onclick="window.location.href='inc/edit_product.php?id=<?php echo $product['id']; ?>'"></i>

<i style="color: #F44336;" class="bx bx-trash-alt icon-tooltip" title="Delete" onclick="deleteProduct(<?php echo $product['id']; ?>)"></i>


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

function deleteProduct(id) {
    if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = 'inc/delete_product.php?id=' + id;
    }
}

</script>
<style>
 .icon-tooltip {
    position: relative;
    display: inline-block;
    font-size: 20px; /* Icon size */
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

.add-product-btn {
    background-color: #3B71CA;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    float: right; /* Align to the right */
    margin-bottom: 15px;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 70px;
    margin-left: 20px;
}

.add-product-btn:hover {
    background-color: #2851a3;
    transform: translateY(-2px);
}

.add-product-btn:active {
    transform: translateY(0);
}

</style>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Icon Furniture</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
     
      Designed by <a href="https://volvrit.com/">Volvrit</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
  <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>


</body>

</html>