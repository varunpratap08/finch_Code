<?php
require 'db.php';

$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : null;

if ($subcategory) {
    // Get the category ID for this subcategory to fetch related subcategories
    $subCatStmt = $pdo->prepare("SELECT * FROM subcategory WHERE subcategory_name = ?");
    $subCatStmt->execute([$subcategory]);
    $subcategoryData = $subCatStmt->fetch(PDO::FETCH_ASSOC);
    $subcategory_id = $subcategoryData ? $subcategoryData['id'] : null;
    $category_id = $subcategoryData ? $subcategoryData['category_id'] : null;
    
    // Get all subcategories for this category to show in the subcategory filter
    $allSubcategoriesStmt = $pdo->prepare("SELECT * FROM subcategory WHERE category_id = ?");
    $allSubcategoriesStmt->execute([$category_id]);
    $allSubcategories = $allSubcategoriesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch products for the selected subcategory using multiple approaches to ensure all matching products are found
    $products = [];
    
    // Try with subcategory ID first
    if ($subcategory_id) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE sub_category = ? OR sub_category = ? OR sub_category LIKE ? OR sub_category LIKE ?");
        $stmt->execute([$subcategory_id, $subcategory, "%$subcategory%", "%$subcategory_id%"]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // If no subcategory ID found, try with subcategory name using exact match and LIKE
        $stmt = $pdo->prepare("SELECT * FROM products WHERE sub_category = ? OR sub_category LIKE ?");
        $stmt->execute([$subcategory, "%$subcategory%"]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // If still no products found, try with a more flexible approach for special cases like "Cupboard Lock"
    if (empty($products) && ($subcategory == "Cupboard Lock" || stripos($subcategory, "cupboard") !== false)) {
        $stmt = $pdo->query("SELECT * FROM products WHERE sub_category LIKE '%cupboard%' OR sub_category LIKE '%Cupboard%'");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Create the subcategory container with proper ID and populate with subcategories
    echo "<div class='row mt-2 g-2 mb-2' id='subcatcontiner2'>";
    
    // Display all subcategories for this category
    foreach ($allSubcategories as $sub) {
        $isActive = ($sub['subcategory_name'] === $subcategory) ? 'active-subcategory' : '';
        echo "<div class='col-md-6 col-12 text-center p-2 borderhover subcategory-item {$isActive}' 
                 data-subcategory='{$sub['subcategory_name']}' 
                 onclick=\"fetchsubcateproducts('{$sub['subcategory_name']}', this)\">
                <span class='mb-3'>{$sub['subcategory_name']}</span>
             </div>";
    }
    
    echo "</div>";

    // Display products
    echo "<div class='row'>";
    if ($products) {
        // Process and display each product
        foreach ($products as $product) {
            $pricing_data = json_decode($product['pricing'], true);
            $sizes = [];
            $finishes = [];
            $finish_labels = [
                'sn_price' => 'Satin Nickel', 
                'bk_price' => 'Black', 
                'an_price' => 'Antique Nickel',
                'gd_price' => 'Gold', 
                'rg_price' => 'Rose Gold', 
                'ch_price' => 'Chrome', 
                'gl_price' => 'Glossy'
            ];
            
            // Extract available sizes and finishes
            if (!empty($pricing_data)) {
                foreach ($pricing_data as $price_row) {
                    if (!empty($price_row['size']) && !in_array($price_row['size'], $sizes)) {
                        $sizes[] = $price_row['size'];
                    }
                }
                
                // Get first price data for default display
                $first_price_data = $pricing_data[0];
                $size = isset($first_price_data['size']) ? htmlspecialchars($first_price_data['size']) : "N/A";
                $price = "N/A";
                $finish = "N/A";
                
                foreach ($finish_labels as $key => $label) {
                    if (!empty(trim($first_price_data[$key]))) {
                        $price = "₹ " . htmlspecialchars(trim($first_price_data[$key]));
                        $finish = $label;
                        break;
                    }
                }
            }

            // Output product card with modern design and working dropdowns
            echo "<div class='col-md-4 col-sm-6 mb-4'>
                <div class='product-card shadow rounded-4 p-3 bg-white position-relative' style='border: 1px solid #f1c40f;'>
                    <div class='product-image text-center mb-2'>
                        <img src='" . htmlspecialchars($product['product_image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='img-fluid rounded-3' style='max-height:180px;object-fit:contain;'>
                    </div>
                    <div class='product-title-wrapper mt-2 mb-1'>
                        <h2 class='product-title fs-5 fw-bold text-dark mb-1'>" . htmlspecialchars($product['product_name']) . "</h2>
                    </div>
                    <div class='product-description mb-2 text-secondary small' style='min-height:40px;'>
                        " . (isset($product['description']) ? htmlspecialchars($product['description']) : '') . "
                    </div>";
            
            // Only show size and finish dropdowns if we have pricing data
            if (!empty($pricing_data)) {
                echo "<div class='product-details d-flex align-items-center' style='gap: 12px;'>";
                
                // Size dropdown
                if (!empty($sizes)) {
                    echo "<div class='dropdown'>
                        <button class='btn btn-sm btn-warning dropdown-toggle px-3 py-2 fw-semibold text-dark border-0 shadow-sm' type='button' id='sizeDropdown-" . $product['id'] . "' data-bs-toggle='dropdown' aria-expanded='false' style='min-width: 90px; border-radius: 20px;'>
                            <i class='bi bi-arrows-angle-expand me-1'></i> Size
                        </button>
                        <ul class='dropdown-menu rounded-3 shadow-sm' aria-labelledby='sizeDropdown-" . $product['id'] . "'>";
                        
                    // Add size options
                    foreach ($sizes as $size_option) {
                        echo "<li><a class='dropdown-item py-2' href='#' onclick=\"event.preventDefault();document.getElementById('sizeDropdown-" . $product['id'] . "').innerHTML='<i class=\\\"bi bi-arrows-angle-expand me-1\\\"</i> " . htmlspecialchars($size_option) . "';\"><i class='bi bi-arrows-angle-expand me-1'></i>" . htmlspecialchars($size_option) . "</a></li>";
                    }
                    
                    echo "</ul>
                    </div>";
                }
                
                // Finish dropdown
                echo "<div class='dropdown'>
                    <button class='btn btn-sm btn-outline-dark dropdown-toggle px-3 py-2 fw-semibold border-0 shadow-sm' type='button' id='finishDropdown-" . $product['id'] . "' data-bs-toggle='dropdown' aria-expanded='false' style='min-width: 110px; border-radius: 20px;'>
                        <i class='bi bi-palette2 me-1'></i> Finish
                    </button>
                    <ul class='dropdown-menu rounded-3 shadow-sm' aria-labelledby='finishDropdown-" . $product['id'] . "'>";
                    
                // Add finish options
                foreach ($finish_labels as $key => $finish_option) {
                    if (isset($first_price_data[$key]) && !empty(trim($first_price_data[$key]))) {
                        echo "<li><a class='dropdown-item py-2' href='#' onclick=\"event.preventDefault();document.getElementById('finishDropdown-" . $product['id'] . "').innerHTML='<i class=\\\"bi bi-palette2 me-1\\\"</i> " . htmlspecialchars($finish_option) . "';\"><i class='bi bi-palette2 me-1'></i>" . htmlspecialchars($finish_option) . "</a></li>";
                    }
                }
                
                echo "</ul>
                    </div>
                </div>";
            }
            
            echo "<div class='product-buttons d-flex gap-2 mt-3'>
                    <a href='product-details.php?id=" . urlencode($product['id']) . "' class='buy-now btn btn-sm btn-warning w-100 fw-semibold'>View Details</a>
                </div>
            </div>
        </div>";
        }
    } else {
        echo "<p class='alert alert-info'>No products found for this subcategory.</p>";
    }
    echo "</div>";
} else {
    echo "<p class='alert alert-warning'>Invalid subcategory.</p>";
}
?>
