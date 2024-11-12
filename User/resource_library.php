<?php
// Start the session and check if the user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the navbar
include 'navbar.php';

// Database connection (customize as needed for your project)
$pdo = new PDO("mysql:host=localhost;dbname=security", "root", ""); // Update with actual DB credentials

// Handle resource upload if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resourceName = $_POST['resource_name'];
    $resourceDescription = $_POST['resource_description'];

    // Upload file handling
    if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['resource_file']['tmp_name'];
        $fileName = $_FILES['resource_file']['name'];
        $destination = 'uploads/' . $fileName;

        // Move the file to the uploads folder
        if (move_uploaded_file($fileTmpPath, $destination)) {
            // Insert resource into database
            $stmt = $pdo->prepare("INSERT INTO resources (name, description, file_path, file_name, upload_date, user_id) VALUES (?, ?, ?, ?, NOW(), ?)");
            $stmt->execute([$resourceName, $resourceDescription, $destination, $fileName, $_SESSION['user_id']]);
            echo "<div class='alert alert-success'>Resource uploaded successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error uploading file. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Please select a file to upload.</div>";
    }
}

// Fetch all resources for display
$stmt = $pdo->query("SELECT * FROM resources");
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Resource Library</title>
    <style>
        .container {
            margin-top: 20px;
            display: flex;
            gap: 20px;
        }
        .resource-library, .upload-form {
            flex: 1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .upload-form {
            background-color: #f9f9f9;
            max-width: 300px;
        }
        .resource-library {
            background-color: #fff;
        }
        .resource-item {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .resource-item p {
            margin: 0;
        }
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .view-toggle button {
            flex: 1;
        }
        .grid-view .resource-item {
            display: inline-block;
            width: 45%;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Resource Library List -->
    <div class="resource-library">
        <h2>Available Resources</h2>
        
        <div class="view-toggle">
            <button id="list-view-btn" class="btn btn-secondary">List View</button>
            <button id="grid-view-btn" class="btn btn-secondary">Grid View</button>
        </div>
        
        <div id="resource-list" class="list-view">
            <?php if (count($resources) > 0): ?>
                <?php foreach ($resources as $resource): ?>
                    <div class="resource-item">
                        <strong><?php echo htmlspecialchars($resource['name']); ?></strong>
                        <p><?php echo htmlspecialchars($resource['description']); ?></p>
                        <p><small>File: <?php echo htmlspecialchars($resource['file_name']); ?></small></p>
                        <p><small>Uploaded on: <?php echo htmlspecialchars($resource['upload_date']); ?></small></p>
                        <a href="<?php echo $resource['file_path']; ?>" download>Download</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No resources available yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Resource Upload Form -->
    <div class="upload-form">
        <h2>Upload a New Resource</h2>
        <form action="resource_library.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="resource_name" class="form-label">Resource Name</label>
                <input type="text" class="form-control" id="resource_name" name="resource_name" required>
            </div>
            <div class="mb-3">
                <label for="resource_description" class="form-label">Resource Description</label>
                <textarea class="form-control" id="resource_description" name="resource_description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="resource_file" class="form-label">Choose File</label>
                <input type="file" class="form-control" id="resource_file" name="resource_file" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Resource</button>
        </form>
    </div>
</div>

<script>
    // Toggle between list and grid views
    document.getElementById("list-view-btn").addEventListener("click", function() {
        document.getElementById("resource-list").classList.remove("grid-view");
        document.getElementById("resource-list").classList.add("list-view");
    });

    document.getElementById("grid-view-btn").addEventListener("click", function() {
        document.getElementById("resource-list").classList.remove("list-view");
        document.getElementById("resource-list").classList.add("grid-view");
    });
</script>

</body>
</html>
