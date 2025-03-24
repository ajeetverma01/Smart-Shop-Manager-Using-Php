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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM stock WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Item deleted successfully.";
        $stmt = $conn->prepare("ALTER TABLE stock DROP COLUMN id;");
        $stmt->execute();
        $stmt = $conn->prepare("ALTER TABLE stock ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;");
        $stmt->execute();
    } else {
        $message = "Error deleting item: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch items from the database
$result = $conn->query("SELECT * FROM stock");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Stock Item</title>
    <link rel="stylesheet" href="deleteStock.css"> <!-- Link to CSS file -->
</head>
<body>
    <h1>Delete Stock Item</h1>

    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <form method="POST" action="">
        <label for="id">Item ID:</label>
        <input type="number" name="id" placeholder="Enter Item ID" required>
        <button type="submit">Delete Item</button>
    </form>

    <h2>Current Inventory</h2>
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
                    <td colspan="5">No items found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>
