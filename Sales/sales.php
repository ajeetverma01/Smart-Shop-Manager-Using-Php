<?php
$host = 'localhost'; 
$user = 'root';
$password = '';
$database = 'inventory_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id']) && isset($_POST['quantity_sold']) && isset($_POST['customer_name'])) {
    $item_id = intval($_POST['item_id']);
    $quantity_sold = intval($_POST['quantity_sold']);
    $customer_name = $conn->real_escape_string(trim($_POST['customer_name']));

    $result = $conn->query("SELECT quantity_available FROM items WHERE id = $item_id");
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $current_quantity = $item['quantity_available'];

        // Check if enough stock is available
        if ($current_quantity >= $quantity_sold) {
            // Update the inventory
            $new_quantity = $current_quantity - $quantity_sold;
            $conn->query("UPDATE items SET quantity_available = $new_quantity WHERE id = $item_id");

            // Record the sale
            $conn->query("INSERT INTO sales (item_id, quantity_sold, customer_name) VALUES ($item_id, $quantity_sold, '$customer_name')");

            $message = "Sale recorded successfully. New quantity: $new_quantity";
        } else {
            $message = "Not enough stock available.";
        }
    } else {
        $message = "Item not found.";
    }
}

if (isset($_POST['remove_sale_id'])) {
    $sale_id = intval($_POST['remove_sale_id']);
    $conn->query("DELETE FROM sales WHERE id = $sale_id");
}

$salesResult = $conn->query("SELECT s.id, s.item_id, s.quantity_sold, s.sale_date, i.name, s.customer_name FROM sales s JOIN items i ON s.item_id = i.id");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Sale</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="number"], input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        #salesTableContainer {
            margin-top: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Record a Sale</h1>
    <form method="POST">
        <label for="item_id">Item ID:</label>
        <input type="number" name="item_id" required>
        
        <label for="quantity_sold">Quantity Sold:</label>
        <input type="number" name="quantity_sold" required>
        
        <label for="customer_name">Customer Name:</label>
        <input type="text" name="customer_name" required>
        
        <button type="submit">Submit Sale</button>
    </form>

    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <h2>Sales Records</h2>
    <button id="showSalesButton">Show Sales</button>
    <div id="salesTableContainer" style="display:none;">
        <table>
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Item Name</th>
                    <th>Quantity Sold</th>
                    <th>Sale Date</th>
                    <th>Customer Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($salesResult->num_rows > 0): ?>
                    <?php while ($sale = $salesResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $sale['id']; ?></td>
                            <td><?php echo htmlspecialchars($sale['name']); ?></td>
                            <td><?php echo $sale['quantity_sold']; ?></td>
                            <td><?php echo $sale['sale_date']; ?></td>
                            <td><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="remove_sale_id" value="<?php echo $sale['id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to remove this sale?');">Remove Sale</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No sales records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('showSalesButton').addEventListener('click', function() {
            const salesTableContainer = document.getElementById('salesTableContainer');
            const isVisible = salesTableContainer.style.display === 'block';
            salesTableContainer.style.display = isVisible ? 'none' : 'block';
            this.textContent = isVisible ? 'Show Sales' : 'Hide Sales';
        });
    </script>
</body>
</html>
