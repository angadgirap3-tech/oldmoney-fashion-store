<?php
include('db.php');
session_start();

// --- SIGNUP ---
if (isset($_POST['signup'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

  // Check if user already exists
  $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($check) > 0) {
    $error = "Account already exists. Please login.";
  } else {
    $insert = mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
    if ($insert) {
      $success = "Signup successful! Please login now.";
    } else {
      $error = "Something went wrong. Please try again.";
    }
  }
}

// --- LOGIN ---
if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = $_POST['password'];

  $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row['password'])) {
      // ✅ Store complete session details for authentication
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user'] = $row['name'];
      $_SESSION['email'] = $row['email'];

      // Redirect to home after login
      header("Location: index.php");
      exit;
    } else {
      $error = "Incorrect password.";
    }
  } else {
    $error = "No account found with that email.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Signup | Old Money Fashion</title>
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
      max-width: 400px;
      margin: 100px auto;
      background: rgba(255,255,255,0.05);
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
    }

    h1 {
      text-align: center;
      font-family: 'Playfair Display', serif;
      color: #d9cbb5;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 20px;
    }

    input {
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

    .toggle-link {
      text-align: center;
      margin-top: 10px;
      font-size: 0.9em;
      color: #d9cbb5;
      cursor: pointer;
    }

    .message {
      text-align: center;
      margin-bottom: 10px;
      color: #f2dede;
    }

    .success {
      color: #d9cbb5;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="logo">Old Money Fashion</div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="orders.php">view orders</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li><a href="auth.php" class="active">Signup</a></li>
      <li><a href="about.php">About Us</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="feedback.php">Feedback</a></li>
    </ul>
  </nav>
</header>

<div class="container">
  <h1 id="form-title">Login</h1>

  <?php if(isset($error)) echo "<p class='message'>$error</p>"; ?>
  <?php if(isset($success)) echo "<p class='message success'>$success</p>"; ?>

  <!-- Login Form -->
  <form id="login-form" method="POST" style="display:block;">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button><br><br>
  </form>

  <!-- Signup Form -->
  <form id="signup-form" method="POST" style="display:none;">
    <input type="text" name="name" placeholder="Full Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="signup">Sign Up</button><br><br>
  </form>

  <div class="toggle-link" onclick="toggleForm()">New user? Sign up here</div>
</div>

<script>
  let isLogin = true;
  function toggleForm() {
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");
    const title = document.getElementById("form-title");
    const toggleText = document.querySelector(".toggle-link");

    if (isLogin) {
      loginForm.style.display = "none";
      signupForm.style.display = "block";
      title.textContent = "Sign Up";
      toggleText.textContent = "Already have an account? Login here";
    } else {
      loginForm.style.display = "block";
      signupForm.style.display = "none";
      title.textContent = "Login";
      toggleText.textContent = "New user? Sign up here";
    }
    isLogin = !isLogin;
  }
</script>

</body>
</html>
