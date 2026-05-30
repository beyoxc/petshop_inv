<?php
include 'includes/auth.php';
include 'config/database.php';
include 'includes/header.php';

$query = "SELECT * FROM categories ORDER BY name";
$result = mysqli_query($inventory_conn, $query);
?>

<h1 class="page-title">Categories</h1>

<div class="table-card">
    <a href="add_category.php" class="btn">Add Category</a>
    <br><br>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>
                <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                <a href="delete_category.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
            </td>
        </tr>
        <?php } ?>

    </table>
</div>

<?php include 'includes/footer.php'; ?>