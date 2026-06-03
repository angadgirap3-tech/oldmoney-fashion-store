<?php
include('db.php');
session_start();

$success_msg = $_SESSION['contact_success'] ?? '';
$error_msg   = $_SESSION['contact_error'] ?? '';
unset($_SESSION['contact_success'], $_SESSION['contact_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | Old Money Fashion</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
<style>
body {
  margin: 0;
  font-family: 'Montserrat', sans-serif;
  animation: gradientShift 15s ease infinite;
  background: linear-gradient(270deg, #0c0c0c, #2e2e2e, #a38d5a, #d9cbb5);
  background-size: 800% 800%;
  color: #fff;
}
@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Navbar */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 60px;
  background: rgba(0,0,0,0.6);
  backdrop-filter: blur(6px);
  position: sticky;
  top: 0;
  z-index: 100;
}
.navbar .logo {
  font-family: 'Playfair Display', serif;
  font-size: 1.8em;
  font-weight: 600;
  color: #d9cbb5;
}
.navbar ul {
  list-style: none;
  display: flex;
  gap: 25px;
  margin: 0;
  padding: 0;
}
.navbar a {
  text-decoration: none;
  color: #fff;
  font-weight: 400;
  transition: color 0.3s ease;
}
.navbar a:hover,
.navbar a.active {
  color: #d9cbb5;
}

/* Container */
.container {
  max-width: 600px;
  margin: 100px auto;
  background: rgba(255,255,255,0.05);
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
h1 {
  font-family: 'Playfair Display', serif;
  color: #d9cbb5;
  text-align: center;
  margin-bottom: 25px;
}
input, textarea {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 6px;
  margin-bottom: 15px;
  font-family: 'Montserrat', sans-serif;
}
button {
  padding: 12px 25px;
  background: #d9cbb5;
  border: none;
  border-radius: 5px;
  font-weight: 500;
  cursor: pointer;
  width: 100%;
  transition: background 0.3s ease;
}
button:hover { background: #bda87f; }
.success-msg { color: #4BB543; text-align: center; margin-bottom: 15px; }
.error-msg { color: #FF4C4C; text-align: center; margin-bottom: 15px; }
</style>
</head>
<body>

<!-- Navbar -->
<header class="navbar">
  <div class="logo">Old Money Fashion</div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="orders.php">view orders</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li><a href="auth.php">Signup</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="contact.php" class="active">Contact Us</a></li>
      <li><a href="about.php">About Us</a></li>
    </ul>
  </nav>
</header>

<!-- Contact Form -->
<div class="container">
  <h1>Contact Us</h1>

  <?php if($success_msg) echo "<div class='success-msg'>$success_msg</div>"; ?>
  <?php if($error_msg) echo "<div class='error-msg'>$error_msg</div>"; ?>

  <form action="submit_contact.php" method="POST">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
    <button type="submit">Send Message</button>
  </form>
</div>

</body>
</html>
