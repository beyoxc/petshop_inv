<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

if (isset($_POST['save'])) {
    $name = mysqli_real_escape_string($inventory_conn, trim($_POST['name']));

    if ($name !== '') {
        mysqli_query(
            $inventory_conn,
            "INSERT INTO categories (name) VALUES ('$name')"
        );
        header('Location: categories.php');
        exit;
    }
}
?>

<h1 class="page-title">Add Category</h1>

<form method="POST" class="form-card">
    <label>Category Name</label>
    <input type="text" name="name" required>
    <button type="submit" name="save" class="btn">Save Category</button>
</form>

<?php include 'includes/footer.php'; ?>