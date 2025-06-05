
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    body {
    background-color: #f4f7f6;
    font-family: 'Poppins', sans-serif;
}

.section {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.card {
    width: 60%;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.card-title {
    text-align: center;
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}
 
 textarea, .name{
     width: 100%;
 }

.form-control, .form-select,  {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 10px;
    transition: 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
}

.mb-3 {
    margin-bottom: 15px;
}

.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.table th {
    background: #007bff;
    color: white;
    text-align: center;
    padding: 12px;
}

.table td {
    background: #fff;
    text-align: center;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.btn-success, .btn-danger, .btn-primary {
    border-radius: 8px;
    font-size: 14px;
    padding: 10px 15px;
}

.btn-primary {
    width: 100%;
    font-size: 16px;
    font-weight: 600;
}

.btn-danger {
    padding: 5px 10px;
    font-size: 12px;
}

    </style>
</head>
<body>
    <?php
require '../../inc/db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];

    // Handle pricing JSON update
    $pricing_data = [];
    foreach ($_POST['sizes'] as $key => $size) {
        $pricing_data[] = [
            'size' => $size,
            'sn_price' => $_POST['sn_price'][$key] ?? '',
            'bk_price' => $_POST['bk_price'][$key] ?? '',
            'an_price' => $_POST['an_price'][$key] ?? '',
            'gd_price' => $_POST['gd_price'][$key] ?? '',
            'rg_price' => $_POST['rg_price'][$key] ?? '',
            'ch_price' => $_POST['ch_price'][$key] ?? '',
            'gl_price' => $_POST['gl_price'][$key] ?? ''
        ];
    }
    $pricing_json = json_encode($pricing_data);

    // Handle image update
    if (!empty($_FILES['product_image']['name'])) {
        $target_dir = "../../uploads/product/";
        $image_name = time() . "_" . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $stored_path = "uploads/product/" . $image_name;
        } else {
            echo "Failed to upload image.";
            exit;
        }
    } else {
        $stored_path = $product['product_image'];
    }

    try {
        $stmt = $pdo->prepare("UPDATE products SET product_name = ?, description = ?, pricing = ?, product_image = ? WHERE id = ?");
        $stmt->execute([$product_name, $description, $pricing_json, $stored_path, $_GET['id']]);
        echo "Product updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Edit Product</h5>
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label>Product Name</label>
          <input type="text" class="form-control name" name="product_name" value="<?= $product['product_name'] ?>">
        </div>

        

        <div class="mb-3">
          <label>Product Description</label>
          <textarea class="form-control" name="description" rows="4"> <?= $product['description'] ?></textarea>
        </div>

        <div class="mb-3">
          <label>Pricing</label>
          <div id="pricing-container">
            <?php $pricing_data = json_decode($product['pricing'], true);
            if (!empty($pricing_data)) {
                foreach ($pricing_data as $price) { ?>
                  <div class="pricing-row">
                    <input type="text" name="sizes[]" class="form-control" placeholder="Size" value="<?= $price['size'] ?>">
                    <input type="text" name="sn_price[]" class="form-control" placeholder="SN Price" value="<?= $price['sn_price'] ?>">
                    <input type="text" name="bk_price[]" class="form-control" placeholder="BK Price" value="<?= $price['bk_price'] ?>">
                    <input type="text" name="an_price[]" class="form-control" placeholder="AN Price" value="<?= $price['an_price'] ?>">
                    <input type="text" name="gd_price[]" class="form-control" placeholder="GD Price" value="<?= $price['gd_price'] ?>">
                    <input type="text" name="rg_price[]" class="form-control" placeholder="RG Price" value="<?= $price['rg_price'] ?>">
                    <input type="text" name="ch_price[]" class="form-control" placeholder="CH Price" value="<?= $price['ch_price'] ?>">
                    <input type="text" name="gl_price[]" class="form-control" placeholder="GL Price" value="<?= $price['gl_price'] ?>">
                  </div>
            <?php }
            } ?>
          </div>
        </div>

        <div class="mb-3">
          <label>Product Image</label>
          <input type="file" class="form-control" name="product_image" accept="image/*">
          <img src="../<?= $product['product_image'] ?>" width="100">
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary">Update Product</button>
        </div>
      </form>
    </div>
  </div>
</section>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("category").addEventListener("change", function () {
        let category = this.value;
        fetch("fetch_subcategories.php?category=" + category)
            .then(response => response.json())
            .then(data => {
                let subCategorySelect = document.getElementById("sub_category");
                subCategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                data.forEach(sub => {
                    let option = document.createElement("option");
                    option.value = sub;
                    option.textContent = sub;
                    subCategorySelect.appendChild(option);
                });
            });
    });
});
</script>

</body>
</html>



