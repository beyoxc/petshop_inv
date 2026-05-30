<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$product = mysqli_fetch_assoc(
    mysqli_query(
        $inventory_conn,
        "SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id='$id'"
    )
);

if (!$product) {
    header('Location: products.php');
    exit;
}

$lowStockThreshold = $product['reorder_threshold'] ?: 5;
$stockMessage = $product['quantity'] <= $lowStockThreshold ? 'Low stock - reorder soon' : 'Stock level is healthy';
?>

<h1 class="page-title">Product Stock Details</h1>

<div class="form-card">
    <div class="detail-grid">
        <div>
            <label>Product Name</label>
            <p><?php echo htmlspecialchars($product['product_name']); ?></p>
        </div>
        <div>
            <label>Category</label>
            <p><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
        </div>
        <div>
            <label>Quantity Available</label>
            <p><?php echo (int) $product['quantity']; ?></p>
        </div>
        <div>
            <label>Reorder Threshold</label>
            <p><?php echo (int) $lowStockThreshold; ?></p>
        </div>
        <div>
            <label>Price</label>
            <p>₱<?php echo number_format($product['price'], 2); ?></p>
        </div>
        <div>
            <label>Supplier</label>
            <p><?php echo htmlspecialchars($product['supplier']); ?></p>
        </div>
        <div>
            <label>Stock Status</label>
            <p class="stock-status <?php echo $product['quantity'] <= $lowStockThreshold ? 'status-low' : 'status-ok'; ?>">
                <?php echo htmlspecialchars($stockMessage); ?>
            </p>
        </div>
    </div>

    <div class="detail-actions">
        <a href="products.php" class="btn">Back to Products</a>
        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn">Edit Product</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>