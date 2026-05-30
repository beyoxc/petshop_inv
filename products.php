<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

$query = "
SELECT products.*, categories.name AS category_name
FROM products
LEFT JOIN categories
ON products.category_id = categories.id
";

$result = mysqli_query($inventory_conn, $query);
?>

<h1 class="page-title">Products</h1>

<div class="table-card">
    <div class="table-actions">
        <a href="add_product.php" class="btn">Add Product</a>
        <input type="search" id="productSearch" placeholder="Search products..." />
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Reorder Level</th>
            <th>Status</th>
            <th>Price</th>
            <th>Supplier</th>
            <th>Action</th>
        </tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['product_name']; ?></td>

<td><?php echo $row['category_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td><?php echo $row['reorder_threshold']; ?></td>

<td>
    <span class="stock-status <?php echo $row['quantity'] <= $row['reorder_threshold'] ? 'status-low' : 'status-ok'; ?>">
        <?php echo $row['quantity'] <= $row['reorder_threshold'] ? 'Low' : 'Good'; ?>
    </span>
</td>

<td>₱<?php echo $row['price']; ?></td>

<td><?php echo $row['supplier']; ?></td>

<td>
    <a href="view_product.php?id=<?php echo $row['id']; ?>" class="btn">View</a>
    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
    <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
</td>

</tr>

<?php } ?>

    </table>
</div>

<script>
    const productSearch = document.getElementById('productSearch');
    productSearch.addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table-card table tr');

        rows.forEach((row, index) => {
            if (index === 0) return;
            const cells = Array.from(row.querySelectorAll('td'));
            const text = cells.map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

<?php include 'includes/footer.php'; ?>