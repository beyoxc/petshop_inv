<?php

$inventory_conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "petshop_inventory"
);

$sales_conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "petshop_sales"
);

if (!$inventory_conn) {
    die("Inventory Database Connection Failed");
}

if (!$sales_conn) {
    die("Sales Database Connection Failed");
}

?>