<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

$total_sales = mysqli_fetch_assoc(
    mysqli_query(
        $sales_conn,
        "SELECT SUM(total_price) as total FROM sales"
    )
);

$total_sales_qty = mysqli_fetch_assoc(
    mysqli_query(
        $sales_conn,
        "SELECT SUM(quantity_sold) as total FROM sales"
    )
);

$top_products = mysqli_query(
    $sales_conn,
    "SELECT p.product_name, SUM(s.quantity_sold) as total_qty, SUM(s.total_price) as total_revenue
     FROM sales s
     JOIN petshop_inventory.products p ON s.product_id = p.id
     GROUP BY s.product_id
     ORDER BY total_revenue DESC
     LIMIT 5"
);

$inventory_summary = mysqli_fetch_assoc(
    mysqli_query(
        $inventory_conn,
        "SELECT COUNT(*) as total_products, SUM(quantity) as total_quantity FROM products"
    )
);

$low_stock_count = mysqli_fetch_assoc(
    mysqli_query(
        $inventory_conn,
        "SELECT COUNT(*) as count FROM products WHERE quantity <= COALESCE(reorder_threshold, 5)"
    )
);

$category_sales = mysqli_query(
    $inventory_conn,
    "SELECT c.name, COUNT(p.id) as product_count
     FROM categories c
     LEFT JOIN products p ON c.id = p.category_id
     GROUP BY c.id
     ORDER BY product_count DESC"
);
?>

<h1 class="page-title">Reports & Analytics</h1>

<div class="report-grid">
    <div class="card report-card">
        <h3>Total Revenue</h3>
        <p class="report-number">₱<?php echo number_format($total_sales['total'] ?? 0, 2); ?></p>
    </div>

    <div class="card report-card">
        <h3>Total Units Sold</h3>
        <p class="report-number"><?php echo (int) ($total_sales_qty['total'] ?? 0); ?></p>
    </div>

    <div class="card report-card">
        <h3>Total Products</h3>
        <p class="report-number"><?php echo (int) ($inventory_summary['total_products'] ?? 0); ?></p>
    </div>

    <div class="card report-card">
        <h3>Low Stock Items</h3>
        <p class="report-number" style="color: #e54dc8;"><?php echo (int) ($low_stock_count['count'] ?? 0); ?></p>
    </div>
</div>

<div class="table-card">
    <h3>Top 5 Best Selling Products</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Units Sold</th>
            <th>Revenue</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($top_products)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td><?php echo (int) $row['total_qty']; ?></td>
            <td>₱<?php echo number_format($row['total_revenue'] ?? 0, 2); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<div class="table-card">
    <h3>Products by Category</h3>
    <table>
        <tr>
            <th>Category</th>
            <th>Product Count</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($category_sales)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name'] ?? 'Uncategorized'); ?></td>
            <td><?php echo (int) $row['product_count']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include 'includes/footer.php'; ?>