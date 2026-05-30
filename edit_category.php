<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

$id = $_GET['id'];

$category = mysqli_fetch_assoc(
    mysqli_query(
        $inventory_conn,
        "SELECT * FROM categories WHERE id='$id'"
    )
);

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($inventory_conn, trim($_POST['name']));

    if ($name !== '') {
        mysqli_query(
            $inventory_conn,
            "UPDATE categories SET name='$name' WHERE id='$id'"
        );
        header('Location: categories.php');
        exit;
    }
}
?>

<h1 class="page-title">Edit Category</h1>

<form method="POST" class="form-card">
    <label>Category Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
    <button type="submit" name="update" class="btn">Update Category</button>
</form>

<?php include 'includes/footer.php'; ?>