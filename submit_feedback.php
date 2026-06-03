<?php
include('db.php');
session_start();

// Check if user is logged in
$user_logged_in = isset($_SESSION['user_id']);
$user_id = $user_logged_in ? $_SESSION['user_id'] : NULL;

if (isset($_POST['feedback'])) {
    // Sanitize input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['feedback']);

    // Validate input
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['feedback_error'] = "All fields are required!";
        header("Location: feedback.php");
        exit;
    }

    // ✅ Fixed column names
    $sql = "INSERT INTO feedback (user_name, email, message)
            VALUES ('$name', '$email', '$message')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['feedback_success'] = "Thank you! Your feedback has been submitted.";
    } else {
        $_SESSION['feedback_error'] = 'Database Error: ' . mysqli_error($conn);
    }

    header("Location: feedback.php");
    exit;
} else {
    header("Location: feedback.php");
    exit;
}
?>
