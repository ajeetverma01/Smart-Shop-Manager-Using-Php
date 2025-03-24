<?php
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$database = 'inventory_db';

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    $dbMessage = "Database created successfully<br>";
} else {
    $dbMessage = "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db($database);

$sql = "CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cost_price DECIMAL(10, 2) NOT NULL,
    selling_price DECIMAL(10, 2) NOT NULL,
    quantity_available INT NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    $tableMessage = "Items table created successfully";
} else {
    $tableMessage = "Error creating items table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cost_price DECIMAL(10, 2) NOT NULL,
    selling_price DECIMAL(10, 2) NOT NULL,
    quantity_available INT NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    $tableMessage .= "<br>Stock table created successfully";
} else {
    $tableMessage .= "<br>Error creating stock table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantity_sold INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id)
)";

if ($conn->query($sql) === TRUE) {
    $tableMessage .= "<br>Sales table created successfully";
} else {
    $tableMessage .= "<br>Error creating sales table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    item_bought VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    $tableMessage .= "<br>Customers table created successfully";
} else {
    $tableMessage .= "<br>Error creating customers table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(100) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    hire_date DATE NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    $tableMessage .= "<br>Staff table created successfully";
} else {
    $tableMessage .= "<br>Error creating staff table: " . $conn->error;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup the Database</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Setup Inventory Database</h1>
    <p>Click the button below to create the database and tables.</p>
    <button id="setupButton">Create Database and Tables</button>
    <div id="result"></div>

    <script>
        document.getElementById('setupButton').addEventListener('click', function() {
            document.getElementById('result').innerHTML = '<?php echo $dbMessage . $tableMessage; ?>';
        });
    </script>
</body>
</html>
