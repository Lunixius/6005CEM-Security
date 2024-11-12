<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Handle logout action
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: user_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #000;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            color: #fff;
        }

        /* Style for each section of the navbar with extra spacing */
        .navbar-left, .navbar-center, .navbar-right {
            display: flex;
            align-items: center;
        }

        /* Make space around the elements */
        .navbar-left {
            flex: 1;
            justify-content: flex-start;
        }

        .navbar-center {
            flex: 1;
            justify-content: center;
        }

        .navbar-right {
            flex: 1;
            justify-content: flex-end;
        }

        /* Username styling */
        .username {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
            background-color: #4CAF50;
            border-radius: 50px;
            padding: 8px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        .username:hover {
            background-color: #45a049;
        }

        /* Navbar link styling */
        .navbar-custom a {
            color: #fff;
            font-weight: 500;
            margin: 0 10px;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }
        .navbar-custom a:hover {
            color: #ddd;
        }

        /* Logout button styling */
        .logout-btn {
            color: red;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="navbar-custom">
        <!-- Left section with username -->
        <div class="navbar-left">
            <span class="username"><?php echo $_SESSION['username']; ?></span>
        </div>

        <!-- Center section with page links -->
        <div class="navbar-center">
            <a href="resource_library.php">Library</a>
            <a href="contacts.php">Contacts</a>
        </div>

        <!-- Right section with logout -->
        <div class="navbar-right">
            <a href="?logout=true" class="logout-btn">Logout</a>
        </div>
    </div>

</body>
</html>
