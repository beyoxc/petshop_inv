<?php
include 'includes/auth.php';
include 'config/database.php';

$id = $_GET['id'];

if ($id) {
    mysqli_query($inventory_conn, "DELETE FROM categories WHERE id='$id'");
}

header('Location: categories.php');
exit;
