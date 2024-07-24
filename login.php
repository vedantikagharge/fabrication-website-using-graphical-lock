<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $images = array($_FILES["image1"]["tmp_name"], $_FILES["image2"]["tmp_name"], $_FILES["image3"]["tmp_name"]);
    
    $stmt = $conn->prepare("SELECT image1, image2, image3 FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_image1, $db_image2, $db_image3);
    $stmt->fetch();
    $stmt->close();
    
    // Save uploaded images to temporary files
    $temp_images = array();
    foreach ($images as $image) {
        $temp_file = tempnam(sys_get_temp_dir(), 'img');
        move_uploaded_file($image, $temp_file);
        $temp_images[] = $temp_file;
    }

    if (hash_file('md5', $temp_images[0]) == hash_file('md5', $db_image1) &&
        hash_file('md5', $temp_images[1]) == hash_file('md5', $db_image2) &&
        hash_file('md5', $temp_images[2]) == hash_file('md5', $db_image3)) {
        $_SESSION['message'] = "Login successful!";
        header("Location: http://localhost/Pankaj%20Fabrication/");
        exit();
    } else {
        $_SESSION['message'] = "Invalid login sequence.";
    }

    // Clean up temporary files
    foreach ($temp_images as $temp_image) {
        unlink($temp_image);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    <form action="login.php" method="post" enctype="multipart/form-data">
        <h2>Login Form</h2>
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
        <button type="submit">Login</button>
    </form>
</body>
</html>
