<?php include('db.php'); ?>
<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Old Money Fashion | Timeless Elegance</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    /* ===== Background Gradient Animation ===== */
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
      padding: 20px 50px;
      background: rgba(14, 147, 147, 0.65);
      backdrop-filter: blur(8px);
      position: sticky;
      top: 0;
      z-index: 100;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .navbar .logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.9em;
      font-weight: 600;
      color: #d9cbb5;
      letter-spacing: 1px;
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
      transition: color 0.3s ease, border-bottom 0.3s ease;
    }

    .navbar a:hover,
    .navbar a.active {
      color: #d9cbb5;
      border-bottom: 2px solid #d9cbb5;
      padding-bottom: 2px;
    }

    .btn-outline {
      border: 1px solid #d9cbb5;
      padding: 6px 14px;
      border-radius: 4px;
      color: #d9cbb5;
      transition: background 0.3s ease, color 0.3s ease;
    }

    .btn-outline:hover {
      background: #d9cbb5;
      color: #000;
    }

    /* ===== Hero Section ===== */
    .hero {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 85vh;
      text-align: center;
      flex-direction: column;
      color: #fff;
    }

    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: 3em;
      margin-bottom: 20px;
    }

    .hero p {
      max-width: 600px;
      margin: 0 auto 30px auto;
      font-size: 1.1em;
    }

    .btn {
      background: #d9cbb5;
      color: #000;
      padding: 12px 26px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: 500;
      transition: transform 0.3s ease, background 0.3s ease;
    }

    .btn:hover {
      background: #bda87f;
      transform: translateY(-3px);
    }

    /* ===== About Section ===== */
    .about {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 80px 60px;
      background: rgba(255, 255, 255, 0.05);
      border-top: 1px solid rgba(255,255,255,0.1);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .about-container {
      display: flex;
      align-items: center;
      gap: 60px;
      max-width: 1100px;
      flex-wrap: wrap;
    }

    .about-text {
      flex: 1;
      color: #fff;
    }

    .about-text h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2em;
      color: #d9cbb5;
      margin-bottom: 20px;
    }

    .about-text p {
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .about-image {
      flex: 1;
    }

    .about-image img {
      width: 100%;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }

    /* ===== Newsletter ===== */
    .newsletter {
      text-align: center;
      padding: 80px 20px;
      background: rgba(0, 0, 0, 0.5);
    }

    .newsletter h2 {
      font-family: 'Playfair Display', serif;
      color: #d9cbb5;
      margin-bottom: 15px;
    }

    .newsletter p {
      color: #eee;
      margin-bottom: 25px;
    }

    .newsletter form {
      display: inline-flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .newsletter input {
      padding: 12px 16px;
      border: none;
      border-radius: 4px;
      width: 280px;
      max-width: 100%;
    }

    .newsletter button {
      padding: 12px 18px;
      border: none;
      background: #d9cbb5;
      color: #000;
      border-radius: 4px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .newsletter button:hover {
      background: #bda87f;
    }

    /* ===== Footer ===== */
    footer {
      text-align: center;
      padding: 25px;
      background: rgba(0, 0, 0, 0.6);
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    .footer-content p {
      margin: 5px 0;
      color: #ccc;
      font-size: 0.9em;
    }

    /* ===== Responsive ===== */
    @media (max-width: 850px) {
      .navbar {
        flex-direction: column;
        padding: 15px 20px;
      }
      .navbar ul {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
      }
      .hero h1 {
        font-size: 2.4em;
      }
      .about {
        flex-direction: column;
        padding: 60px 30px;
      }
      .about-image img {
        max-width: 90%;
      }
    }
  </style>
</head>

<body>

  

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Timeless Style. Eternal Class.</h1>
      <p>Explore the refined world of Old Money Fashion — where elegance meets heritage.</p>
      <a href="products.php" class="btn">Explore Collection</a>
    </div>
  </section>

  <!-- About Section -->
  <section class="about">
    <div class="about-container">
      <div class="about-text">
        <h2>About Old Money Fashion</h2>
        <p>
          Inspired by the sophistication of classic heritage, Old Money Fashion embodies a sense of understated luxury.
          Our pieces reflect tradition, craftsmanship, and the art of quiet confidence — designed for those who value quality over trend.
        </p>
        <a href="about.php" class="btn-outline">Learn More</a>
      </div>
      <div class="about-image">
        
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <p>&copy; <?php echo date("Y"); ?> Old Money Fashion. All rights reserved.</p>
      <p>Crafted with elegance & taste.</p>
    </div>
  </footer>

</body>
</html>
