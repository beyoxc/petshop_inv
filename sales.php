<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

if(isset($_POST['sell'])) {

    $product_id = $_POST['product_id'];
    $quantity_sold = $_POST['quantity_sold'];

    $product = mysqli_fetch_assoc(
        mysqli_query(
            $inventory_conn,
            "SELECT * FROM products WHERE id='$product_id'"
        )
    );

    $total_price =
        $product['price'] * $quantity_sold;

    mysqli_query(
        $sales_conn,
        "INSERT INTO sales(
            product_id,
            quantity_sold,
            total_price
        )

        VALUES(
            '$product_id',
            '$quantity_sold',
            '$total_price'
        )"
    );

    $new_stock =
        $product['quantity'] - $quantity_sold;

    mysqli_query(
        $inventory_conn,
        "UPDATE products
         SET quantity='$new_stock'
         WHERE id='$product_id'"
    );
}

$products = mysqli_query(
    $inventory_conn,
    "SELECT * FROM products"
);

$sales = mysqli_query(
    $sales_conn,
    "SELECT * FROM sales ORDER BY id DESC"
);
?>

<h1>Sales</h1>

<form method="POST">

<label>Product</label>

<select name="product_id">

<?php while($product = mysqli_fetch_assoc($products)) { ?>

<option value="<?php echo $product['id']; ?>">

<?php echo $product['product_name']; ?>

</option>

<?php } ?>

</select>

<label>Quantity Sold</label>

<input type="number"
name="quantity_sold"
required>

<button type="submit"
name="sell"
class="btn">

Record Sale

</button>

</form>

<br>

<table>

<tr>
<th>ID</th>
<th>Product ID</th>
<th>Quantity</th>
<th>Total</th>
<th>Date</th>
</tr>

<?php while($sale = mysqli_fetch_assoc($sales)) { ?>

<tr>

<td><?php echo $sale['id']; ?></td>

<td><?php echo $sale['product_id']; ?></td>

<td><?php echo $sale['quantity_sold']; ?></td>

<td>₱<?php echo $sale['total_price']; ?></td>

<td><?php echo $sale['sale_date']; ?></td>

</tr>

<?php } ?>

</table>

<?php include 'includes/footer.php'; ?>