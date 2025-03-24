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

// Handle form submission for adding a new staff member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_staff'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];

    // Insert new staff member into the database
    $sql = "INSERT INTO staff (name, position, salary, hire_date) VALUES ('$name', '$position', '$salary', '$hire_date')";
    if ($conn->query($sql) === TRUE) {
        $message = "New staff member added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Handle form submission for firing a staff member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fire_staff'])) {
    $staff_id = $_POST['staff_id'];

    // Delete staff member from the database
    $sql = "DELETE FROM staff WHERE id = '$staff_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "Staff member fired successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all staff members
$sql = "SELECT * FROM staff";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <link rel="stylesheet" href="staff.css">
</head>
<body>
    <h1>Manage Staff</h1>

    <?php if (isset($message)): ?>
        <div style="background-color: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <h2>Add New Staff Member</h2>
        <input type="text" name="name" placeholder="Staff Name" required>
        <input type="text" name="position" placeholder="Position" required>
        <input type="number" name="salary" placeholder="Salary" required>
        <input type="date" name="hire_date" required>
        <input type="submit" name="add_staff" value="Add Staff">
    </form>

    <h2>Staff List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Salary</th>
            <th>Hire Date</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo $row['salary']; ?></td>
                    <td><?php echo $row['hire_date']; ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="staff_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" name="fire_staff" value="Fire" class="fire-button" onclick="return confirm('Are you sure you want to fire this staff member?');">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No staff members found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php $conn->close(); ?>
</body>
</html>
