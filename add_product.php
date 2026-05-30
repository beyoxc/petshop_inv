<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

if(isset($_POST['save'])) {

    $category_id = $_POST['category_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $supplier = $_POST['supplier'];
    $reorder_threshold = $_POST['reorder_threshold'];

    $query = "
    INSERT INTO products(
        category_id,
        product_name,
        quantity,
        reorder_threshold,
        price,
        supplier
    )

    VALUES(
        '$category_id',
        '$product_name',
        '$quantity',
        '$reorder_threshold',
        '$price',
        '$supplier'
    )
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

<h1 class="page-title">Add Product</h1>

<form method="POST" class="form-card">

<label>Category</label>
<select name="category_id" <?php echo $hasCategories ? '' : 'disabled'; ?>>
    <?php if (!$hasCategories) : ?>
        <option>No categories available. Add categories first.</option>
    <?php else: ?>
        <?php while($cat = mysqli_fetch_assoc($categories)) { ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
        <?php } ?>
    <?php endif; ?>
</select>

<label>Product Name</label>
<input type="text" name="product_name" required>

<label>Quantity</label>
<input type="number" name="quantity" required>

<label>Reorder Threshold</label>
<input type="number" name="reorder_threshold" value="5" min="1" required>

<label>Price</label>
<input type="number" step="0.01" name="price" required>

<label>Supplier</label>
<input type="text" name="supplier">

<button type="submit" name="save" class="btn" <?php echo $hasCategories ? '' : 'disabled'; ?>>
    Save Product
</button>

</form>

<?php include 'includes/footer.php'; ?>