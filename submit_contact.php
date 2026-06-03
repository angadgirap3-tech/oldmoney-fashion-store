<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $_SESSION['contact_success'] = "Thank you! Your message has been sent successfully.";
        } else {
            $_SESSION['contact_error'] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    } else {
        $_SESSION['contact_error'] = "Please fill in all the fields.";
    }
}

header("Location: contact.php");
exit();
?>
