<?php
session_start();
include 'connect.php';

// Ensure uploads directory exists
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    
    $image1 = $target_dir . basename($_FILES["image1"]["name"]);
    $image2 = $target_dir . basename($_FILES["image2"]["name"]);
    $image3 = $target_dir . basename($_FILES["image3"]["name"]);
    
    // Check if the uploads were successful
    if (move_uploaded_file($_FILES["image1"]["tmp_name"], $image1) &&
        move_uploaded_file($_FILES["image2"]["tmp_name"], $image2) &&
        move_uploaded_file($_FILES["image3"]["tmp_name"], $image3)) {
        
        $stmt = $conn->prepare("INSERT INTO admin_users (name, username, image1, image2, image3) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $username, $image1, $image2, $image3);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Signup successful!";
            header("Location: request.php");
            exit();
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $_SESSION['message'] = "Sorry, there was an error uploading your files.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <form action="request_1.php" method="post" enctype="multipart/form-data">
        <h2>Admin Signup Form</h2>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="image1">Image 1:</label>
            <input type="file" id="image1" name="image1" required>
        </div>
        <div class="form-group">
            <label for="image2">Image 2:</label>
            <input type="file" id="image2" name="image2" required>
        </div>
        <div class="form-group">
            <label for="image3">Image 3:</label>
            <input type="file" id="image3" name="image3" required>
        </div>
        <button type="submit">Signup</button>
        <p>Already have an account? <a href="request.php">Login</a></p>
    </form>
</body>
</html>
