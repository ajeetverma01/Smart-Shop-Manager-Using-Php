<?php
$host = 'localhost'; // Change if needed
$user = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
$database = 'inventory_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch items from the database
$result = $conn->query("SELECT * FROM stock");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock List</title>
    <link rel="stylesheet" href="Stock.css"> <!-- Link to CSS file -->
</head>
<body>
    <h1>Stock List</h1>

    <div class="button-container">
        <a href="addStock.php" class="button">Add Item</a>
        <a href="deleteStock.php" class="button">Delete Item</a>
        <a href="updateStock.php" class="button">Update Item</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
                <th>Quantity Available</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($item = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['cost_price'], 2); ?></td>
                        <td>$<?php echo number_format($item['selling_price'], 2); ?></td>
                        <td><?php echo $item['quantity_available']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No stocks found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>
