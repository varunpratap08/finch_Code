<?php
require_once 'inc/db.php';

// Check if product_id is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product.");
}

$product_id = $_GET['id'];

try {
    // Fetch product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }

    // Decode JSON pricing data
    $pricing = json_decode($product['pricing'], true);
    $sizeDetails = [];
    $finishDetails = [];

    if (is_array($pricing)) {
        foreach ($pricing as $entry) {
            $sizeDetails[] = $entry['size'];

            // Only show finishes where the price is greater than 0
            $finishes = [];
            if (!empty($entry['sn_price']) && $entry['sn_price'] > 0) {
                $finishes[] = "SN: &#8377;{$entry['sn_price']}";
            }
            if (!empty($entry['bk_price']) && $entry['bk_price'] > 0) {
                $finishes[] = "BK: &#8377;{$entry['bk_price']}";
            }
            if (!empty($entry['an_price']) && $entry['an_price'] > 0) {
                $finishes[] = "AN: &#8377;{$entry['an_price']}";
            }
            if (!empty($entry['gd_price']) && $entry['gd_price'] > 0) {
                $finishes[] = "GD: &#8377;{$entry['gd_price']}";
            }
            if (!empty($entry['rg_price']) && $entry['rg_price'] > 0) {
                $finishes[] = "RG: &#8377;{$entry['rg_price']}";
            }

            if (!empty($finishes)) {
                $finishDetails[] = implode(" | ", $finishes);
            }
        }
    }

    $sizeText = !empty($sizeDetails) ? implode(', ', $sizeDetails) : 'N/A';
    $finishText = !empty($finishDetails) ? implode('<br>', $finishDetails) : 'N/A';

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  
  <style>
        .product-details {
            padding: 120px 0;
        }
        .product-image img {
            width: 520px;
            height: 400px;
            border-radius: 10px;
            object-fit: cover;
        }
        .product-title {
            font-size: 28px;
            font-weight: bold;
        }
        .product-price {
            font-size: 22px;
            color: #28a745;
            font-weight: bold;
        }
        .product-description {
            font-size: 16px;
            color: #6c757d;
        }
        .btn-custom {
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>

<body class="index-page">

  <?php include ('inc/header.php'); ?>

  <main class="main">

   



  <!-- Product Details Section -->
<div class="container product-details">
    <div class="row align-items-center">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="product-image-container text-center mb-4">
                <img src="<?php echo htmlspecialchars($product['product_image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                     class="img-fluid product-img rounded shadow-sm">
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="col-md-6">
            <div class="product-details-container mb-4">
                <h2 class="product-title mb-3"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <div class="product-description mb-4">
                    <?php 
                    $description = html_entity_decode($product['description']);
                    
                    // Format key details with bold text
                    $keywords = array(
                        'Material:' => '<strong class="detail-label">Material:</strong>',
                        'Suitable for:' => '<strong class="detail-label">Suitable for:</strong>',
                        'Features:' => '<strong class="detail-label">Features:</strong>',
                        'Dimensions:' => '<strong class="detail-label">Dimensions:</strong>',
                        'Weight:' => '<strong class="detail-label">Weight:</strong>',
                        'Color:' => '<strong class="detail-label">Color:</strong>',
                        'Finish:' => '<strong class="detail-label">Finish:</strong>',
                        'Installation:' => '<strong class="detail-label">Installation:</strong>',
                        'Warranty:' => '<strong class="detail-label">Warranty:</strong>',
                        'Package Includes:' => '<strong class="detail-label">Package Includes:</strong>'
                    );
                    
                    // Replace keywords with bold formatting
                    foreach ($keywords as $keyword => $replacement) {
                        $description = str_replace($keyword, $replacement, $description);
                    }
                    
                    // Split by newlines to process each line
                    $lines = explode("\n", $description);
                    $formattedDescription = '';
                    
                    foreach ($lines as $line) {
                        // Check if line contains a detail label
                        if (strpos($line, 'detail-label') !== false) {
                            // This is a new detail section, add a div wrapper
                            $formattedDescription .= '<div class="product-detail-item">' . $line;
                        } else if (trim($line) !== '') {
                            // This is content, possibly belonging to a detail section
                            $formattedDescription .= '<span class="detail-content">' . $line . '</span>';
                        }
                        
                        // Add closing div if needed
                        if (strpos($line, 'detail-label') !== false) {
                            $formattedDescription .= '</div>';
                        }
                    }
                    
                    echo $formattedDescription;
                    ?>
                </div>
            </div>
            
            <style>
                .product-title {
                    font-weight: 600;
                    color: #333;
                    border-bottom: 2px solid #f0f0f0;
                    padding-bottom: 10px;
                }
                .product-description {
                    line-height: 1.8;
                    color: #555;
                    font-size: 1.05rem;
                    background-color: #f9f9f9;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                }
                .product-detail-item {
                    margin-bottom: 12px;
                    padding-bottom: 12px;
                    border-bottom: 1px dashed #e0e0e0;
                }
                .product-detail-item:last-child {
                    border-bottom: none;
                    margin-bottom: 0;
                    padding-bottom: 0;
                }
                .detail-label {
                    color: #222;
                    font-weight: 700;
                    font-size: 1.1rem;
                    display: block;
                    margin-bottom: 5px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .detail-content {
                    display: block;
                    padding-left: 10px;
                    font-weight: 500;
                    color: #444;
                }
                .form-label.fw-bold {
                    font-size: 0.9rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    color: #555;
                }
                #mainSelectedPrice {
                    font-size: 1.5rem;
                    font-weight: 600;
                }
                .product-image-container {
                    padding: 15px;
                    background-color: #fff;
                    border-radius: 8px;
                }
                .product-img {
                    max-height: 400px;
                    width: auto;
                    object-fit: contain;
                }
            </style>

            <!-- Size and Finish Selection -->
            <div class="product-options-container p-4 bg-light rounded shadow-sm mb-4">
                <h4 class="mb-3">Product Options</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="mainSizeSelect" class="form-label fw-bold">Size</label>
                        <select id="mainSizeSelect" class="form-select form-select-lg">
                            <option value="">Choose Size</option>
                            <?php foreach ($pricing as $entry) { ?>
                                <option value="<?php echo htmlspecialchars($entry['size']); ?>" 
                                    data-sn="<?php echo $entry['sn_price']; ?>" 
                                    data-bk="<?php echo $entry['bk_price']; ?>" 
                                    data-an="<?php echo $entry['an_price']; ?>" 
                                    data-gd="<?php echo $entry['gd_price']; ?>" 
                                    data-rg="<?php echo $entry['rg_price']; ?>"
                                    data-ch="<?php echo $entry['ch_price']; ?>"
                                    data-gl="<?php echo $entry['gl_price']; ?>">
                                    <?php echo htmlspecialchars($entry['size']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mainFinishSelect" class="form-label fw-bold">Finish</label>
                        <select id="mainFinishSelect" class="form-select form-select-lg">
                            <option value="">Choose Finish</option>
                            <option value="sn">Satin Nickel</option>
                            <option value="bk">Black</option>
                            <option value="an">Antique Nickel</option>
                            <option value="gd">Gold</option>
                            <option value="rg">Rose Gold</option>
                            <option value="ch">Chrome</option>
                            <option value="gl">Glossy</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mainQuantity" class="form-label fw-bold">Quantity</label>
                        <input type="number" id="mainQuantity" class="form-control form-control-lg" value="1" min="1">
                    </div>
                </div>
                
                <!-- Price display removed as requested -->
            </div>

            <!-- Dimension Chart Image -->
            <div class="dimension-chart-container mt-3 mb-3 text-center">
                <img src="assets/img/Finch_Dimension-Chart_1.jpg" alt="Finch Dimension Chart" class="img-fluid rounded shadow-sm">
            </div>

            <!-- Buttons -->
            <div class="action-buttons d-flex gap-3 mt-3">
                <button class="btn btn-custom btn-lg px-4 py-2" id="buyNowBtn">
                    <i class="bi bi-bag-check me-2"></i>Buy Now
                </button>
                <button class="btn btn-dark btn-lg px-4 py-2" id="addToCartBtn">
                    <i class="bi bi-cart-plus me-2"></i>Add To Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Buy Now Form -->
<div class="modal fade" id="buyNowModal" tabindex="-1" aria-labelledby="buyNowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyNowModalLabel">Buy Now - <?php echo htmlspecialchars($product['product_name']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="buyNowForm" action="inc/order_api.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="customer_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="customer_email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" name="customer_phone" required>
                    </div>
                    
                    

                    <div class="mb-3">
                        <label class="form-label">Shipping Address</label>
                        <textarea class="form-control" name="customer_address" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Size, Finish & Quantity</label>
                        <div id="sizeFinishContainer">
                            <div class="input-group mb-2">
                                <select class="form-select size-option" name="sizes[]" required onchange="updateFinishOptions(this)">
                                    <option value="">Choose Size</option>
                                    <?php foreach ($pricing as $entry) { ?>
                                        <option value="<?php echo htmlspecialchars($entry['size']); ?>" 
                                            data-sn="<?php echo $entry['sn_price']; ?>" 
                                            data-bk="<?php echo $entry['bk_price']; ?>" 
                                            data-an="<?php echo $entry['an_price']; ?>" 
                                            data-gd="<?php echo $entry['gd_price']; ?>" 
                                            data-rg="<?php echo $entry['rg_price']; ?>"
                                            data-ch="<?php echo $entry['ch_price']; ?>"
                                            data-gl="<?php echo $entry['gl_price']; ?>">
                                            <?php echo htmlspecialchars($entry['size']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <select class="form-select finish-option" name="finishes[]" required>
                                    <option value="">Choose Finish</option>
                                    <option value="sn">Satin Nickel</option>
                                    <option value="bk">Black</option>
                                    <option value="an">Antique Nickel</option>
                                    <option value="gd">Gold</option>
                                    <option value="rg">Rose Gold</option>
                                    <option value="ch">Chrome</option>
                                    <option value="gl">Glossy</option>
                                </select>
                                <input type="number" class="form-control quantity-option" name="quantities[]" placeholder="Qty" min="1" required>
                                <button type="button" class="btn btn-danger remove-option">✖</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="addOption">+ Add More</button>
                    </div>

                    <div class="mb-3">
                        <h5>Total Price: ₹<span id="totalPrice">0</span></h5>
                        <input type="hidden" name="total_price" id="totalPriceInput">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('addOption').addEventListener('click', function () {
    const container = document.getElementById('sizeFinishContainer');
    const newGroup = document.createElement('div');
    newGroup.classList.add('input-group', 'mb-2');
    newGroup.innerHTML = `
        <select class="form-select size-option" name="sizes[]" required onchange="updateFinishOptions(this)">
            <option value="">Choose Size</option>
            <?php foreach ($pricing as $entry) { ?>
                <option value="<?php echo htmlspecialchars($entry['size']); ?>" 
                    data-sn="<?php echo $entry['sn_price']; ?>" 
                    data-bk="<?php echo $entry['bk_price']; ?>" 
                    data-an="<?php echo $entry['an_price']; ?>" 
                    data-gd="<?php echo $entry['gd_price']; ?>" 
                    data-rg="<?php echo $entry['rg_price']; ?>"
                    data-ch="<?php echo $entry['ch_price']; ?>"
                    data-gl="<?php echo $entry['gl_price']; ?>">
                    <?php echo htmlspecialchars($entry['size']); ?>
                </option>
            <?php } ?>
        </select>
        <select class="form-select finish-option" name="finishes[]" required>
            <option value="">Choose Finish</option>
            <option value="sn">Satin Nickel</option>
            <option value="bk">Black</option>
            <option value="an">Antique Nickel</option>
            <option value="gd">Gold</option>
            <option value="rg">Rose Gold</option>
            <option value="ch">Chrome</option>
            <option value="gl">Glossy</option>
        </select>
        <input type="number" class="form-control quantity-option" name="quantities[]" placeholder="Qty" min="1" required>
        <button type="button" class="btn btn-danger remove-option">✖</button>
    `;
    container.appendChild(newGroup);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-option')) {
        e.target.parentElement.remove();
        updateTotalPrice();
    }
});

document.addEventListener('input', function () {
    updateTotalPrice();
});

function updateTotalPrice() {
    let total = 0;
    document.querySelectorAll('.input-group').forEach(group => {
        const sizeOption = group.querySelector('.size-option');
        const finishOption = group.querySelector('.finish-option');
        const quantity = parseInt(group.querySelector('.quantity-option').value) || 0;
        
        if (sizeOption.value && finishOption.value && quantity > 0) {
            const price = parseFloat(sizeOption.selectedOptions[0].dataset[finishOption.value]) || 0;
            total += price * quantity;
        }
    });
    document.getElementById('totalPrice').innerText = total.toFixed(2);
    document.getElementById('totalPriceInput').value = total.toFixed(2);
}

function updateFinishOptions(selectElement) {
    const finishSelect = selectElement.parentElement.querySelector('.finish-option');
    const selectedSize = selectElement.selectedOptions[0];
    
    // If no size is selected, disable all finish options except the placeholder
    if (!selectedSize || selectedSize.value === '') {
        finishSelect.querySelectorAll('option').forEach((option, index) => {
            option.disabled = index > 0; // Only enable the first option (placeholder)
        });
        finishSelect.value = '';
        return;
    }
    
    // Store the currently selected finish if any
    const currentFinish = finishSelect.value;
    
    // Reset all options first (remove any 'not available' text)
    const finishNames = {
        'sn': 'Satin Nickel',
        'bk': 'Black',
        'an': 'Antique Nickel',
        'gd': 'Gold',
        'rg': 'Rose Gold',
        'ch': 'Chrome',
        'gl': 'Glossy'
    };
    
    finishSelect.querySelectorAll('option').forEach((option, index) => {
        if (index > 0) { // Skip the placeholder
            option.textContent = finishNames[option.value];
            option.style.color = '';
        }
    });
    
    // Enable all options initially
    finishSelect.querySelectorAll('option').forEach(option => {
        option.disabled = false;
    });
    
    // For debugging - log the dataset values
    console.log('Size option dataset:', selectedSize.dataset);
    
    // Make all finishes available for now to ensure the form works
    // We'll refine this later once we confirm the data structure
    
    // Update the total price
    updateTotalPrice();
}
</script>





    

    

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

<script>
// Cart functionality
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
    document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
    document.querySelectorAll('.cart-total').forEach(el => el.textContent = '₹' + total);
}

function showToast(message) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast show';
    toast.style.cssText = 'background-color: #333; color: white; padding: 15px; border-radius: 4px; margin-bottom: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); min-width: 250px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-cart-check me-2" style="color: #DEB462;"></i>
            <div>${message}</div>
            <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove();"></button>
        </div>
    `;
    
    // Add toast to container
    toastContainer.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Add to Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Main product selection functionality
    const mainSizeSelect = document.getElementById('mainSizeSelect');
    const mainFinishSelect = document.getElementById('mainFinishSelect');
    const mainQuantityInput = document.getElementById('mainQuantity');
    
    // Simple product selection logic - no disabling of options
    mainSizeSelect.addEventListener('change', function() {
        console.log('Size selected:', this.value);
    });
    
    mainFinishSelect.addEventListener('change', function() {
        console.log('Finish selected:', this.value);
    });
    
    mainQuantityInput.addEventListener('input', function() {
        console.log('Quantity updated:', this.value);
    });
    
    // Buy Now button handler
    const buyNowBtn = document.getElementById('buyNowBtn');
    const buyNowModal = new bootstrap.Modal(document.getElementById('buyNowModal'));
    
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
            // Check if size and finish are selected
            if (!mainSizeSelect.value) {
                showToast('Please select a size');
                return;
            }
            
            if (!mainFinishSelect.value) {
                showToast('Please select a finish');
                return;
            }
            
            // Get the first row in the Buy Now modal form
            const form = document.getElementById('buyNowForm');
            const modalSizeSelect = form.querySelector('select[name="sizes[]"]');
            const modalFinishSelect = form.querySelector('select[name="finishes[]"]');
            const modalQuantityInput = form.querySelector('input[name="quantities[]"]');
            
            // Set the values from the main dropdowns
            if (modalSizeSelect) {
                // Find and select the matching size option
                for (let i = 0; i < modalSizeSelect.options.length; i++) {
                    if (modalSizeSelect.options[i].value === mainSizeSelect.value) {
                        modalSizeSelect.selectedIndex = i;
                        break;
                    }
                }
                
                // Trigger the change event to update finishes
                const event = new Event('change');
                modalSizeSelect.dispatchEvent(event);
            }
            
            // Set the finish after the size change event has updated available finishes
            setTimeout(() => {
                if (modalFinishSelect) {
                    // Find and select the matching finish option
                    for (let i = 0; i < modalFinishSelect.options.length; i++) {
                        if (modalFinishSelect.options[i].value === mainFinishSelect.value) {
                            modalFinishSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
                
                // Set the quantity
                if (modalQuantityInput) {
                    modalQuantityInput.value = mainQuantityInput.value;
                }
                
                // Show the modal
                buyNowModal.show();
            }, 100);
        });
    }
    
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            // Use the main dropdowns instead of the form
            const sizeSelect = document.getElementById('mainSizeSelect');
            const finishSelect = document.getElementById('mainFinishSelect');
            const quantityInput = document.getElementById('mainQuantity');
            
            if (!sizeSelect.value) {
                showToast('Please select a size');
                return;
            }
            
            if (!finishSelect.value) {
                showToast('Please select a finish');
                return;
            }
            
            const quantity = parseInt(quantityInput.value) || 1;
            
            // Get the selected size option to access price data
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            const finishCode = finishSelect.value;
            const priceAttr = 'data-' + finishCode;
            const price = parseFloat(selectedOption.getAttribute(priceAttr)) || 0;
            
            if (price <= 0) {
                showToast('Selected combination is not available');
                return;
            }
            
            // Get the finish display name
            const finishNames = {
                'sn': 'Satin Nickel',
                'bk': 'Black',
                'an': 'Antique Nickel',
                'gd': 'Gold',
                'rg': 'Rose Gold',
                'ch': 'Chrome',
                'gl': 'Glossy'
            };
            const finishName = finishNames[finishCode] || finishCode;
            
            // Add to cart
            const product = {
                id: <?php echo json_encode($product['id']); ?>,
                name: <?php echo json_encode($product['product_name']); ?>,
                price: price,
                size: sizeSelect.value,
                finish: finishCode,
                finishName: finishName,
                image: <?php echo json_encode($product['product_image']); ?>,
                qty: 1
            };
            
            // Get current cart
            const cart = getCart();
            
            // Check if product already exists in cart
            const existingProductIndex = cart.findIndex(item => 
                item.id === product.id && 
                item.size === product.size && 
                item.finish === product.finish
            );
            
            if (existingProductIndex > -1) {
                // If product exists, increase quantity
                cart[existingProductIndex].qty += 1;
            } else {
                // If product doesn't exist, add it to cart
                cart.push(product);
            }
            
            // Save updated cart to localStorage
            setCart(cart);
            
            // Update cart UI
            updateCartUI();
            
            // Show success message
            const originalText = addToCartBtn.textContent;
            addToCartBtn.innerHTML = '<i class="bi bi-check-circle"></i> Added!';
            addToCartBtn.disabled = true;
            
            // Reset button after 2 seconds
            setTimeout(() => {
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = false;
            }, 2000);
            
            // Show toast notification
            showToast(`${product.name} added to cart!`);
        });
    }
    
    // Initialize cart UI
    updateCartUI();
});
</script>

</body>

</html>