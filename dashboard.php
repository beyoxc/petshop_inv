<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

$product_count = mysqli_fetch_assoc(
    mysqli_query(
        $inventory_conn,
        "SELECT COUNT(*) as total FROM products"
    )
);

$sales_count = mysqli_fetch_assoc(
    mysqli_query(
        $sales_conn,
        "SELECT COUNT(*) as total FROM sales"
    )
);

$total_stock = mysqli_fetch_assoc(
    mysqli_query(
        $inventory_conn,
        "SELECT SUM(quantity) as total_stock FROM products"
    )
);

$category_stats = mysqli_query(
    $inventory_conn,
    "SELECT categories.name AS category_name, COUNT(products.id) AS total_products
     FROM categories
     LEFT JOIN products ON products.category_id = categories.id
     GROUP BY categories.id"
);

$low_stock = mysqli_query(
    $inventory_conn,
    "SELECT products.*, categories.name AS category_name
     FROM products
     LEFT JOIN categories ON products.category_id = categories.id
     WHERE products.quantity <= COALESCE(products.reorder_threshold, 5)
     ORDER BY products.quantity ASC
     LIMIT 6"
);

$top_sellers = mysqli_query(
    $sales_conn,
    "SELECT p.product_name, SUM(s.quantity_sold) AS total_qty, SUM(s.total_price) AS total_revenue
     FROM sales s
     JOIN petshop_inventory.products p ON s.product_id = p.id
     GROUP BY s.product_id
     ORDER BY total_qty DESC
     LIMIT 5"
);

$top_seller = mysqli_fetch_assoc(
    mysqli_query(
        $sales_conn,
        "SELECT p.product_name, SUM(s.quantity_sold) AS total_qty
         FROM sales s
         JOIN petshop_inventory.products p ON s.product_id = p.id
         GROUP BY s.product_id
         ORDER BY total_qty DESC
         LIMIT 1"
    )
);

$chart_labels = [];
$chart_values = [];
while ($row = mysqli_fetch_assoc($category_stats)) {
    $chart_labels[] = $row['category_name'] ?: 'Uncategorized';
    $chart_values[] = (int) $row['total_products'];
}
?>

<div class="hero-card">
    <div class="hero-copy">
        <p class="eyebrow">Just Four Paws Tracking System</p>
        <h1>Track stock with a friendlier pet retailer dashboard.</h1>
        <p>View total inventory, low-stock alerts, reorder levels, and category trends in one delightful place.</p>
    </div>
    <div class="hero-placeholder">
        <img src="assets/images/just4paws.jpg" alt="Just Four Paws placeholder image" class="hero-placeholder-image">
    </div>
</div>

<div class="dashboard-grid">
    <div class="card stat-card">
        <h3>Total Products</h3>
        <p class="stat-number"><?php echo $product_count['total']; ?></p>
    </div>

    <div class="card stat-card">
        <h3>Total Sales</h3>
        <p class="stat-number"><?php echo $sales_count['total']; ?></p>
    </div>

    <div class="card stat-card">
        <h3>Top Seller</h3>
        <p class="stat-number"><?php echo htmlspecialchars($top_seller['product_name'] ?? 'N/A'); ?></p>
        <p style="margin-top: 10px; font-size: 0.95rem; color: #7c4bb5; font-weight: 600;">
            <?php echo (int) ($top_seller['total_qty'] ?? 0); ?> sold
        </p>
    </div>

    <div class="card stat-card">
        <h3>Total Stock Quantity</h3>
        <p class="stat-number"><?php echo (int) $total_stock['total_stock']; ?></p>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card chart-card">
        <div class="chart-header">
            <h3>Products by Category</h3>
            <p>See which pet product groups are stocked most.</p>
        </div>

        <?php if (!empty($chart_labels)) : ?>
            <canvas id="categoryChart" height="220"></canvas>
        <?php else: ?>
            <div class="empty-chart">No categories available yet. Add categories to populate the chart.</div>
        <?php endif; ?>
    </div>

    <div class="card low-stock-card">
        <div class="chart-header">
            <h3>Top Selling Products</h3>
            <p>These products have the highest units sold.</p>
        </div>

        <?php if (mysqli_num_rows($top_sellers)) : ?>
            <ul class="low-stock-list">
                <?php while ($row = mysqli_fetch_assoc($top_sellers)) : ?>
                    <li>
                        <span class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></span>
                        <span class="product-count"><?php echo (int) $row['total_qty']; ?> sold</span>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <div class="empty-chart">No sales recorded yet to display top sellers.</div>
        <?php endif; ?>
    </div>

    <div class="card low-stock-card">
        <div class="chart-header">
            <h3>Low Stock Items</h3>
            <p>Products needing restock soon.</p>
        </div>

        <?php if (mysqli_num_rows($low_stock)) : ?>
            <ul class="low-stock-list">
                <?php while ($row = mysqli_fetch_assoc($low_stock)) : ?>
                    <li>
                        <span class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></span>
                        <span class="product-count"><?php echo (int) $row['quantity']; ?> left / <?php echo (int) $row['reorder_threshold']; ?> threshold</span>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <div class="empty-chart">All products are currently well stocked.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartLabels = <?php echo json_encode($chart_labels); ?>;
    const chartValues = <?php echo json_encode($chart_values, JSON_NUMERIC_CHECK); ?>;

    if (chartLabels.length) {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Products',
                    data: chartValues,
                    backgroundColor: 'rgba(219, 101, 202, 0.85)',
                    borderColor: 'rgba(159, 52, 173, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }
                }
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>