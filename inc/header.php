<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logo.png" alt="">
        <span class="company-name-gradient" style="font-weight: bold; font-size: 1.4rem; margin-left: 22px; background: linear-gradient(90deg, #ffb347 0%, #ffcc33 40%, #ffe259 70%, #ffa751 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-fill-color: transparent; letter-spacing: 1px;">Pali Industries</span>
      </a>
      <style>
      .company-name-gradient {
  font-family: 'Poppins', 'Nunito', Arial, sans-serif;
  font-weight: 800;
  font-size: 1.4rem;
  margin-left: 22px;
  background: linear-gradient(90deg, #ffb347 0%, #ffcc33 40%, #ffe259 70%, #ffa751 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  letter-spacing: 1px;
  transition: font-size 0.2s, margin-left 0.2s;
}

@media only screen and (max-width: 700px) {
.header{
    padding: 0px !important;
}
.btn-getstarted{
    display: none;
}

.company-name-gradient {
    font-size: 1rem;
    margin-left: 12px;
  }
}
@media (max-width: 400px) {
  .company-name-gradient {
    font-size: 0.85rem;
    margin-left: 7px;
  }
}
          
      </style>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="products.php">Products</a></li>
        </ul>
        
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="contact.php">Contact Us</a>
      <div class="cart-header d-flex align-items-center" style="margin-left: 20px;">
        <a href="cart.php" class="cart-link position-relative" style="color: #222; text-decoration: none;">
          <i class="bi bi-cart" style="font-size: 1.7rem;"></i>
          <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.9rem;">0</span>
          <span class="cart-total ms-2" style="font-weight: bold; color: #DEB462;">₹0</span>
        </a>
      </div>

    </div>
  </header>
  <script>
// Ensure cart count/total is updated on every page load
function getCart() {
    return JSON.parse(localStorage.getItem('cart') || '[]');
}
function updateCartUI() {
    const cart = getCart();
    let count = 0, total = 0;
    cart.forEach(item => {
        count += item.qty;
        total += item.price * item.qty;
    });
    document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
    document.querySelectorAll('.cart-total').forEach(el => el.textContent = '₹' + total);
}
document.addEventListener('DOMContentLoaded', updateCartUI);
</script>