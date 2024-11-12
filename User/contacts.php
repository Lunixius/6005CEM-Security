<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection parameters
$servername = "localhost";
$db_username = "root";  
$db_password = "";  
$dbname = "security";

// Create a database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current user's ID from the session
$user_id = $_SESSION['user_id'];

// Debug: Check if user_id is being set correctly
// echo "User ID: " . $user_id; exit;  // Uncomment for debugging

// Fetch all other users (excluding the logged-in user)
$other_user_query = $conn->prepare("SELECT id, username, email FROM user WHERE id != ?");
$other_user_query->bind_param("i", $user_id);
$other_user_query->execute();
$other_user_result = $other_user_query->get_result();

// Debug: Check the SQL query result
// echo "Num rows: " . $other_user_result->num_rows; exit;  // Uncomment for debugging

// Close the prepared statement
$other_user_query->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 90%;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        h3 {
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 80px;
            border: 1px solid #dee2e6;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            text-align: center;
            padding: 12px;
            background-color: #fff;
        }
        .table th {
            background-color: #f8f9fa;
            color: #333;
        }
        .btn-message {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-weight: 500;
            display: inline-block;
        }

        .btn-message:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Contacts</h1>

        <!-- Other User Contacts Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($other_user_result->num_rows > 0): ?>
                    <?php while ($other_user = $other_user_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($other_user['username']); ?></td>
                            <td><?php echo htmlspecialchars($other_user['email']); ?></td>
                            <td><a href="message.php?username=<?php echo urlencode($other_user['username']); ?>" class="btn btn-message">Message</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No contacts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
