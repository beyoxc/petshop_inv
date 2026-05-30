<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

$id = $_GET['id'];

$product = mysqli_fetch_assoc(
    mysqli_query(
        $inventory_conn,
        "SELECT * FROM products WHERE id='$id'"
    )
);

if(isset($_POST['update'])) {

    $category_id = $_POST['category_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $reorder_threshold = $_POST['reorder_threshold'];
    $price = $_POST['price'];
    $supplier = $_POST['supplier'];

    $query = "
    UPDATE products
    SET
        category_id='$category_id',
        product_name='$product_name',
        quantity='$quantity',
        reorder_threshold='$reorder_threshold',
        price='$price',
        supplier='$supplier'
    WHERE id='$id'
    ";

    mysqli_query($inventory_conn, $query);

    header("Location: products.php");
}

$categories = mysqli_query(
    $inventory_conn,
    "SELECT * FROM categories"
);
$hasCategories = mysqli_num_rows($categories) > 0;
?>

<h1 class="page-title">Edit Product</h1>

<form method="POST" class="form-card">

<label>Category</label>
<select name="category_id" <?php echo $hasCategories ? '' : 'disabled'; ?>>
    <?php if (!$hasCategories) : ?>
        <option>No categories available. Add categories first.</option>
    <?php else: ?>
        <?php while($cat = mysqli_fetch_assoc($categories)) { ?>
            <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $product['category_id']) echo 'selected'; ?>>
                <?php echo $cat['name']; ?>
            </option>
        <?php } ?>
    <?php endif; ?>
</select>

<label>Product Name</label>
<input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required>

<label>Quantity</label>
<input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required>

<label>Reorder Threshold</label>
<input type="number" name="reorder_threshold" value="<?php echo $product['reorder_threshold'] ?: 5; ?>" min="1" required>

<label>Price</label>
<input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>

<label>Supplier</label>
<input type="text" name="supplier" value="<?php echo $product['supplier']; ?>">

<button type="submit" name="update" class="btn" <?php echo $hasCategories ? '' : 'disabled'; ?>>
    Update Product
</button>

</form>

<?php include 'includes/footer.php'; ?>