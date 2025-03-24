<?php
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$database = 'inventory_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT s.customer_name, i.name AS item_bought
    FROM sales s
    JOIN items i ON s.item_id = i.id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers List</title>
    <link rel="stylesheet" href="customers.css">
</head>
<body>
    <h1>Customers List</h1>
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Item Bought</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($customer = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['item_bought']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No customers found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>
