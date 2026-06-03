<?php include('db.php'); ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback | Old Money Fashion</title>
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

    /* ===== Navbar ===== */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 60px;
      background: rgba(0, 0, 0, 0.6);
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

    /* ===== Page Content ===== */
    .container {
      max-width: 700px;
      margin: 80px auto;
      padding: 20px;
      text-align: center;
    }

    h1 {
      font-family: 'Playfair Display', serif;
      color: #d9cbb5;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      background: rgba(255,255,255,0.05);
      padding: 30px;
      border-radius: 8px;
    }

    input, textarea {
      padding: 12px;
      border: none;
      border-radius: 4px;
      font-family: 'Montserrat', sans-serif;
    }

    button {
      padding: 12px;
      background: #d9cbb5;
      border: none;
      border-radius: 4px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover { background: #bda87f; }
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
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <li><a href="feedback.php" class="active">Feedback</a></li>
      </ul>
    </nav>
  </header>

  <!-- Feedback Section -->
  <div class="container">
    <h1>We Value Your Feedback</h1>
    <form method="POST" action="submit_feedback.php">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="feedback" rows="5" placeholder="Your Feedback..." required></textarea>
      <button type="submit">Submit Feedback</button>
    </form>
  </div>

  <?php include('footer.php'); ?>

</body>
</html>
