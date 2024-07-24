<?php
$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'];
$email = $data['email'];
$phone_number = $data['phone_number'];
$message = $data['message'];

// Prepare the email
$to = "sakshijadhav5535@gmail.com"; 
$subject = "New Contact Us Message";
$email_message = "Name: $name\n";
$email_message .= "Email: $email\n";
$email_message .= "Phone Number: $phone_number\n";
$email_message .= "Message: $message\n";
$headers = "From: $email";

// Send the email
if (mail($to, $subject, $email_message, $headers)) {
    echo "Email sent successfully";
} else {
    echo "There was a problem sending the email";
}
?>
