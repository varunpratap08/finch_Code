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


    <div class="breadcrumb" style="
    display: flex;
    gap: 29px;
    text-decoration: none;
">
      <ul class="breadcrumb-menu" style=" display: flex;
    gap:3px;">
        <li><a href="index.html"><i class="far fa-home"></i> Home /</a></li>
        <li class="active">Products</li>
      </ul>
    </div>
    <style>
    
    .breadcrumb {
    margin-top: 90px; /* Adjust as needed */
    padding: 10px 20px;
    border-radius: 5px;
   
}
.breadcrumb li{
    list-style: none;
    
    font-weight: 700;

}

section {
    padding:0px;
}

      /*.products {*/
      /*  border-top: 1px solid #ddd;*/
      /*}*/

      .products .products-heading {
        background-color: #DEB462;
        padding: 5px 10px;
        color: white;
        margin-top: 10px;
      }

      .products .products-heading h2 {
        font-size: 18px;
      }

      .category {
        border-top: 1px solid #ddd;

      }

      .category .category-head{
       margin-bottom: 20px;
       color: #252525;
       font-weight: 800 !important;
      }

      .category ul {
        list-style: none;
        padding: 0;
        margin: 0;

      }

      .category ul li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        font-size: 18px;
        cursor: pointer;
      }

      .category ul li i {
        font-size: 16px;
        color: #000000;
      }


      .category ul li:hover {
        background: #FFE9BE;
        color: #000;
        border-color: #FFE9BE;
      }

      
      .product-card-section{
        border-left: 1px solid #ddd;
        border-top: 1px solid #ddd;
      }

/* Product Card */
.product-card {
    width: 100%;
    max-width: 320px;
    padding: 20px 0px;
    text-align: left;
}

/* Product Image */

.product-image img {
    width: 100%;
    max-width: 480px;
    height: 225px;
    padding: 10px;
    border: 1px solid #DEB462;
    object-fit: cover;
}

/* Wrapper to keep title width same as buttons */
.product-title-wrapper {
    display: flex;
    justify-content: left;
}

/* Product Title */
.product-title {
    background: #DEB462;
    color: white;
    padding: 10px 20px;
    font-size: 12px;
    text-transform: capitalize;
    width: 100%;
    max-width: 100%;
    text-align: center;
}

/* Product Details */
.product-details {
    text-align: left;
    padding: 2px;
}

/* Buttons Container */
.product-buttons {
    margin-top: 12px;
    display: flex;
    gap: 10px;
}

/* Make sure buttons and title have the same width */
.buy-now, .add-to-cart, .product-title-wrapper {
    width: 100%;
}

/* Buy Now Button */
.buy-now {
    background: #DEB462;
    color: white;
    border: none;
    padding: 8px;
    /* border-radius: 6px; */
    cursor: pointer;
    flex-grow: 1;
    text-align: center;
    transition: 0.3s;
}
/* Add to Cart Button */
.add-to-cart {
    background: none;
    border: 2px solid #DEB462;
    color: #DEB462;
    font-weight: bold;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
    flex-grow: 1;
    transition: 0.3s;
}

/* Hover Effects */
.buy-now:hover {
    background: #c9a34c;
}

.add-to-cart:hover {
    background: #DEB462;
    color: white;
}

.borderhover:hover{
   border-bottom:2px solid #deb462 !important;
}





    </style>


    <Section class="products">

      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3">
            <div class="category">
              <div class="container category-head">
                <p style="
    font-weight: bold;    font-size:21px;

">Shop By Category</p>
              </div>
              <?php
require 'inc/db.php'; // Database connection

// Fetch all categories with id and name
$categories = $pdo->query("SELECT id, category_name FROM category ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<ul id="category-list">
    <li class="category-item" >
        <a href="products.php">All Products</a><i class="bi-chevron-right"></i>
    </li>
    <?php foreach (
        $categories as $category): ?>
        <li class="category-item" data-category-id="<?php echo htmlspecialchars($category['id']); ?>" data-category="<?php echo htmlspecialchars($category['category_name']); ?>">
            <?php echo htmlspecialchars($category['category_name']); ?> <i class="bi-chevron-right"></i>
        </li>
    <?php endforeach; ?>
</ul>

            </div>
          </div>
          <div class="col-lg-9 col-md-9 product-card-section">
            <div class="knowing" >
              <h2 id="categoryName"></h2>
            </div>
            <div class="row" id="product-container">
            <p>Select a category to view products.</p>
            <script>
document.addEventListener("DOMContentLoaded", function () {
    const categoryItems = document.querySelectorAll(".category-item");
    const productContainer = document.getElementById("product-container");
    const categoryNameElement = document.getElementById("categoryName");

    // Function to fetch and update products
    function fetchProducts(categoryId = "") {
        const url = "inc/fetch_products.php" + (categoryId ? "?category_id=" + encodeURIComponent(categoryId) : "");
        fetch(url)
            .then(response => response.text())
            .then(data => {
                productContainer.innerHTML = data;
                // Set category name
                let activeCat = document.querySelector('.category-item.active');
                if (activeCat) {
                  categoryNameElement.innerHTML = activeCat.getAttribute('data-category') || "All Products";
                } else {
                  categoryNameElement.innerHTML = "All Products";
                }
                document.querySelector('.knowing').classList.add('products-heading');
            })
            .catch(error => console.error("Error fetching products:", error));
    }

    // Load all products initially
    fetchProducts();

    // Fetch products on category click
    categoryItems.forEach(item => {
        item.addEventListener("click", function () {
            categoryItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            const categoryId = this.getAttribute("data-category-id");
            fetchProducts(categoryId);
        });
    });
});
</script>
            </div>

          </div>
        </div>
      </div>
    </Section>

  </main>

  <?php include ('inc/footer.php'); ?>
  
  
  <script>
document.addEventListener("DOMContentLoaded", function () {
    // Function to fetch and display products by subcategory
    function fetchsubcateproducts(subcategoryName, element) {
        console.log("Fetching products for subcategory:", subcategoryName);
        
        // Create and send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "inc/fetch_product_subcat.php?subcategory=" + encodeURIComponent(subcategoryName), true);
        
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Update product container with new products
                const productContainer = document.getElementById("product-container");
                if (productContainer) {
                    productContainer.innerHTML = xhr.responseText;
                    
                    // Set category name in the header
                    const categoryNameElement = document.getElementById("categoryName");
                    if (categoryNameElement) {
                        categoryNameElement.innerHTML = subcategoryName;
                    }
                    
                    // Add heading class if not already present
                    const knowingElement = document.querySelector('.knowing');
                    if (knowingElement && !knowingElement.classList.contains('products-heading')) {
                        knowingElement.classList.add('products-heading');
                    }
                    
                    // Initialize Bootstrap dropdowns in the newly loaded content
                    if (typeof bootstrap !== 'undefined') {
                        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
                        dropdownToggles.forEach(toggle => {
                            new bootstrap.Dropdown(toggle);
                        });
                    }
                    
                    // Highlight the active subcategory
                    if (element) {
                        document.querySelectorAll('.subcategory-item').forEach(item => {
                            item.classList.remove('active-subcategory');
                        });
                        element.classList.add('active-subcategory');
                    }
                    
                    // Re-attach event listeners to any subcategory items in the response
                    document.querySelectorAll('#subcatcontiner2 .subcategory-item').forEach(item => {
                        item.addEventListener('click', function() {
                            const subcat = this.getAttribute('data-subcategory');
                            fetchsubcateproducts(subcat, this);
                        });
                    });
                }
            } else {
                console.error("Error fetching products for subcategory:", subcategoryName);
            }
        };
        
        xhr.onerror = function() {
            console.error("Network error when fetching products for subcategory:", subcategoryName);
        };
        
        xhr.send();
    }

    // Make it global so it can be called from HTML onclick
    window.fetchsubcateproducts = fetchsubcateproducts;
    
    // Also attach event listeners to any subcategory items that exist on page load
    document.querySelectorAll('.subcategory-item').forEach(item => {
        item.addEventListener('click', function() {
            const subcat = this.getAttribute('data-subcategory');
            fetchsubcateproducts(subcat, this);
        });
    });
});
</script>

  
<!-- Removed redundant commented out script -->

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

  <script>
// Cart logic for Add to Cart buttons
function getCart() {
    return JSON.parse(localStorage.getItem('cart') || '[]');
}
function setCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}
function updateCartUI() {
    const cart = getCart();
    let count = 0, total = 0;
    cart.forEach(item => {
        count += item.qty;
        total += item.price * item.qty;
    });
    // Update your cart icon/section here
    document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
    document.querySelectorAll('.cart-total').forEach(el => el.textContent = '₹' + total);
}
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-to-cart')) {
        const btn = e.target;
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        const price = parseFloat(btn.getAttribute('data-price')) || 0;
        const image = btn.getAttribute('data-image');
        let cart = getCart();
        let found = cart.find(item => item.id == id);
        if (found) {
            found.qty += 1;
        } else {
            cart.push({id, name, price, image, qty: 1});
        }
        setCart(cart);
        updateCartUI();
        btn.textContent = "Added!";
        setTimeout(() => btn.textContent = "Add to Cart", 1000);
    }
});
document.addEventListener('DOMContentLoaded', updateCartUI);
</script>

</body>

</html>