<?php include('db.php'); ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Old Money Fashion</title>
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

    .container {
      max-width: 900px;
      margin: 80px auto;
      padding: 20px;
      text-align: center;
    }

    h1 {
      font-family: 'Playfair Display', serif;
      color: #d9cbb5;
      margin-bottom: 30px;
    }

    p {
      font-size: 1.05em;
      line-height: 1.7;
      color: #ddd;
    }
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
      <li><a href="about.php" class="active">About Us</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="feedback.php">Feedback</a></li>
    </ul>
  </nav>
</header>

<div class="container">
  <h1>Our Story</h1>
  <p>
    Old Money Fashion is inspired by the understated elegance and timeless sophistication of heritage luxury.
    We believe true style is not about trends — it’s about confidence, refinement, and quality craftsmanship.
    <br><br>
    Our mission is to bring back the art of classic dressing, with pieces that echo tradition while embracing modern comfort.
    Every collection reflects a blend of European tailoring, premium fabrics, and the quiet confidence of those who know class never shouts.
  </p>
</div>

<?php include('footer.php'); ?>
</body>
</html>
