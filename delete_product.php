<?php
include 'includes/auth.php';
include 'config/database.php';

$id = $_GET['id'];

mysqli_query(
    $inventory_conn,
    "DELETE FROM products WHERE id='$id'"
);

header("Location: products.php");
?>