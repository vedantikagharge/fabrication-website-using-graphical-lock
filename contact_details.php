<?php
include 'database.php'; // Ensure this file contains the correct database connection

// Retrieve contact details from the database
$sql = "SELECT * FROM contact_us";
$result = $conn->query($sql);

$contact_details = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contact_details[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .logout {
            display: block;
            width: 100px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Contact Details</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contact_details as $detail): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detail['name']); ?></td>
                        <td><?php echo htmlspecialchars($detail['email']); ?></td>
                        <td><?php echo htmlspecialchars($detail['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($detail['message']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p><a class="logout" href="index.php">Logout</a></p>
    </div>
</body>
</html>
