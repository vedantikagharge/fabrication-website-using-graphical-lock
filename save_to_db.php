<?php
include('database.inc.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone_number = mysqli_real_escape_string($conn, $_POST["phone_number"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);
    $msg = "";

    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $checkQuery = $conn->prepare("SELECT id FROM contact_us WHERE email = ?");
        $checkQuery->bind_param("s", $email);
        $checkQuery->execute();
        $checkResult = $checkQuery->get_result();

        if ($checkResult->num_rows > 0) {
            $msg = "Email already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO contact_us (name, email, phone_number, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone_number, $message);

            if ($stmt->execute()) {
                $msg = "Entry saved to database.";

                $html = "<table><tr><td>Name</td><td>$name</td></tr><tr><td>Email</td><td>$email</td></tr><tr><td>Mobile</td><td>$phone_number</td></tr><tr><td>Comment</td><td>$message</td></tr></table>";
                
                include('smtp/PHPMailerAutoload.php');
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 587;
                $mail->SMTPSecure = "tls";
                $mail->SMTPAuth = true;
                $mail->Username = "sakshijadhav5535@gmail.com";
                $mail->Password = "ofbd fwnx mzul bpfx";
                $mail->SetFrom("sakshijadhav5535@gmail.com");
                $mail->addAddress("sakshijadhav5535@gmail.com");
                $mail->IsHTML(true);
                $mail->Subject = "New Contact Us";
                $mail->Body = $html;
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => false
                    )
                );

                if ($mail->send()) {
                    $msg .= " Mail sent.";
                } else {
                    $msg .= " Mail sending failed.";
                }
            } else {
                $msg = "Database error: " . $stmt->error;
            }

            $stmt->close();
        }
        $checkQuery->close();
    } else {
        $msg = "Invalid email format.";
    }

    header("Location: contact.php?msg=$msg");
    exit();
}

$conn->close();
?>
