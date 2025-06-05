
<?php
require '../inc/db.php';

// Fetch categories and subcategories
$categories = $pdo->query("SELECT DISTINCT category_name FROM category")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $pdo->query("SELECT DISTINCT subcategory_name FROM subcategory")->fetchAll(PDO::FETCH_ASSOC);

// Initialize default product values
$product = [
    'product_name' => '',
    'category_name' => '',
    'subcategory_name' => '',
    'description' => '',
    'pricing' => '[]', // Default empty JSON
    'product_image' => ''
];

if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $category_name = $_POST['category'];
    $subcategory_name = $_POST['sub_category'];
    $description = $_POST['description'];

    // Convert pricing data to JSON
    $pricing_data = [];
    foreach ($_POST['size'] as $index => $size) {
        $pricing_data[] = [
            'size' => $size,
            'sn_price' => $_POST['sn_price'][$index] ?? '',
            'bk_price' => $_POST['bk_price'][$index] ?? '',
            'an_price' => $_POST['an_price'][$index] ?? '',
            'gd_price' => $_POST['gd_price'][$index] ?? '',
            'rg_price' => $_POST['rg_price'][$index] ?? '',
            'ch_price' => $_POST['ch_price'][$index] ?? '',
            'gl_price' => $_POST['gl_price'][$index] ?? ''
        ];
    }
    $pricing_json = json_encode($pricing_data);

    // Handle image upload
    if (!empty($_FILES['product_image']['name'])) {
        $image_name = time() . '_' . $_FILES['product_image']['name'];
        move_uploaded_file($_FILES['product_image']['tmp_name'], "uploads/$image_name");
    } else {
        $image_name = $product['product_image'] ?? ''; // Keep existing image if editing
    }

    if (isset($_POST['product_id'])) {
        // Update existing product
        $stmt = $pdo->prepare("UPDATE products SET product_name=?, category_name=?, subcategory_name=?, description=?, pricing=?, product_image=? WHERE id=?");
        $stmt->execute([$product_name, $category_name, $subcategory_name, $description, $pricing_json, $image_name, $_POST['product_id']]);
    } else {
        // Insert new product
        $stmt = $pdo->prepare("INSERT INTO products (product_name, category_name, subcategory_name, description, pricing, product_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$product_name, $category_name, $subcategory_name, $description, $pricing_json, $image_name]);
    }

    header("Location: product_list.php");
    exit();
}

$pricing = json_decode($product['pricing'], true) ?: [];
?>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title"><?= isset($_GET['edit_id']) ? 'Edit' : 'Add' ?> Product</h5>

      <form method="POST" action="" enctype="multipart/form-data">
        <?php if (isset($_GET['edit_id'])): ?>
          <input type="hidden" name="product_id" value="<?= $_GET['edit_id'] ?>">
        <?php endif; ?>

        <div class="row">
          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="product_name" class="col-sm-4 col-form-label">Product Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="product_name" id="product_name" value="<?= $product['product_name'] ?>" required>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="category" class="col-sm-4 col-form-label">Category</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="category" value="<?= $product['category_name'] ?>" required>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="sub_category" class="col-sm-4 col-form-label">Sub-Category</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="sub_category" value="<?= $product['subcategory_name'] ?>" required>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="mb-3 row">
              <label for="product_image" class="col-sm-4 col-form-label">Product Image</label>
              <div class="col-sm-8">
                <input type="file" class="form-control" name="product_image" accept="image/*">
                <?php if (!empty($product['product_image'])): ?>
                  <img src="uploads/<?= $product['product_image'] ?>" class="img-thumbnail mt-2" width="100">
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Product Description</label>
          <textarea class="form-control" name="description"><?= $product['description'] ?></textarea>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered">
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
            <tbody id="pricingTable">
              <?php foreach ($pricing as $p): ?>
                <tr>
                  <td><input type="text" class="form-control" name="size[]" value="<?= $p['size'] ?>"></td>
                  <td><input type="text" class="form-control" name="sn_price[]" value="<?= $p['sn_price'] ?>"></td>
                  <td><input type="text" class="form-control" name="bk_price[]" value="<?= $p['bk_price'] ?>"></td>
                  <td><input type="text" class="form-control" name="an_price[]" value="<?= $p['an_price'] ?>"></td>
                  <td><input type="text" class="form-control" name="gd_price[]" value="<?= $p['gd_price'] ?>"></td>
                  <td><input type="text" class="form-control" name="rg_price[]" value="<?= $p['rg_price'] ?>"></td>
                  <td><input type="text" class="form-control" name="ch_price[]" value="<?= $p['ch_price'] ?>"></td>
                  <td><input type="text" class="form-control" name="gl_price[]" value="<?= $p['gl_price'] ?>"></td>
                  <td><button type="button" class="btn btn-danger removeRow">X</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <button type="button" id="addRow" class="btn btn-primary">Add Row</button>
        </div>

        <div class="text-center mt-3">
          <button type="submit" class="btn btn-success"><?= isset($_GET['edit_id']) ? 'Update' : 'Add' ?> Product</button>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
document.getElementById('addRow').addEventListener('click', function() {
    document.getElementById('pricingTable').insertRow().innerHTML = '<td><input type="text" class="form-control" name="size[]"></td>' +
    '<td><input type="text" class="form-control" name="sn_price[]"></td>' +
    '<td><input type="text" class="form-control" name="bk_price[]"></td>' +
    '<td><input type="text" class="form-control" name="an_price[]"></td>' +
    '<td><input type="text" class="form-control" name="gd_price[]"></td>' +
    '<td><input type="text" class="form-control" name="rg_price[]"></td>' +
    '<td><input type="text" class="form-control" name="ch_price[]"></td>' +
    '<td><input type="text" class="form-control" name="gl_price[]"></td>' +
    '<td><button type="button" class="btn btn-danger removeRow">X</button></td>';
});
</script>


<script>
document.getElementById('category').addEventListener('change', function () {
    let categoryId = this.value;
    fetch('inc/fetch_subcategories.php?category_id=' + categoryId)
        .then(response => response.json())
        .then(data => {
            let subCategorySelect = document.getElementById('sub_category');
            subCategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
            data.forEach(sub => {
                subCategorySelect.innerHTML += `<option value="${sub.id}">${sub.subcategory_name}</option>`;
            });
        });
});
</script>

