<?php
// cart.php - Simple Cart Page for Pali Industries
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Pali Industries</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <style>
        .cart-page-container { max-width: 900px; margin: 60px auto 40px; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 32px; }
        .cart-item-img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
        .cart-empty { text-align: center; color: #888; font-size: 1.2rem; margin: 40px 0; }
        .cart-total-row { font-size: 1.3rem; font-weight: bold; border-top: 2px solid #eee; padding-top: 18px; }
        .remove-btn { color: #F44336; cursor: pointer; font-size: 1.2rem; }
    </style>
</head>
<body>
<?php include('inc/header.php'); ?>
<div class="cart-page-container">
    <h2 class="mb-4"><i class="bi bi-cart3"></i> Your Cart</h2>
    <div id="cartPageItems"></div>
    <div class="cart-total-row d-flex justify-content-between mt-4">
        <span>Total:</span>
        <span id="cartPageTotal">₹0</span>
    </div>
    <div class="text-end mt-4">
        <a href="products.php" class="btn btn-outline-secondary">Continue Shopping</a>
        <button class="btn btn-success ms-2" id="checkoutBtn" disabled>Checkout</button>
    </div>
</div>
<script>
function getCart() {
    return JSON.parse(localStorage.getItem('cart') || '[]');
}
function setCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}
function removeFromCartPage(idx) {
    let cart = getCart();
    cart.splice(idx, 1);
    setCart(cart);
    renderCartPage();
    if(typeof updateCartUI === 'function') updateCartUI();
}
function renderCartPage() {
    const cart = getCart();
    const cartDiv = document.getElementById('cartPageItems');
    const cartTotal = document.getElementById('cartPageTotal');
    const checkoutBtn = document.getElementById('checkoutBtn');
    if(cart.length === 0) {
        cartDiv.innerHTML = '<div class="cart-empty">Your cart is empty.</div>';
        cartTotal.textContent = '₹0';
        checkoutBtn.disabled = true;
        return;
    }
    let total = 0;
    cartDiv.innerHTML = cart.map((item, idx) => {
        total += item.price * item.qty;
        return `<div class='d-flex align-items-center border-bottom py-3'>
            <img src='${item.image}' class='cart-item-img me-3' alt='${item.name}'>
            <div class='flex-grow-1'>
                <div><b>${item.name}</b></div>
                <div>Qty: <span class='badge bg-secondary'>${item.qty}</span> × ₹${item.price}</div>
            </div>
            <span class='remove-btn ms-3' onclick='removeFromCartPage(${idx})' title='Remove'>&times;</span>
        </div>`;
    }).join('');
    cartTotal.textContent = '₹' + total;
    checkoutBtn.disabled = false;
}
document.addEventListener('DOMContentLoaded', renderCartPage);
</script>
<?php include('inc/footer.php'); ?>
</body>
</html>
