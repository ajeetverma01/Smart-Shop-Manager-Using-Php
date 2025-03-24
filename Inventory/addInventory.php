<?php
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$database = 'inventory_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $cost_price = $_POST['cost_price'];
    $selling_price = $_POST['selling_price'];
    $quantity_available = $_POST['quantity_available'];

    $stmt = $conn->prepare("INSERT INTO items (name, cost_price, selling_price, quantity_available) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sddi", $name, $cost_price, $selling_price, $quantity_available);

    if ($stmt->execute()) {
        $message = "Item added successfully.";
    } else {
        $message = "Error adding item: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item to Inventory</title>
    <link rel="stylesheet" href="addInventory.css">
</head>
<body>
    <h1>Add Item to Inventory</h1>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Item Name" required>
        <input type="number" name="cost_price" placeholder="Cost Price" step="0.01" required>
        <input type="number" name="selling_price" placeholder="Selling Price" step="0.01" required>
        <input type="number" name="quantity_available" placeholder="Quantity Available" required>
        <button type="submit">Add Item</button>
    </form>

    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
</body>
</html>
