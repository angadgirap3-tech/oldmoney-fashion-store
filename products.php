<?php 
include('db.php'); 
session_start();

// Check if user is logged in
$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user'] ?? 'Guest';
$user_email = $_SESSION['email'] ?? '';

// Initialize session cart for guests
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if(isset($_GET['add'])){
    $product_id = intval($_GET['add']);

    if($user_logged_in){
        // Add to database cart for logged-in users
        $user_id = $_SESSION['user_id'];
        $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
        if(mysqli_num_rows($check) > 0){
            mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id='$user_id' AND product_id='$product_id'");
        } else {
            mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)");
        }
    } else {
        // Add to session cart for guests
        if(isset($_SESSION['cart'][$product_id])){
            $_SESSION['cart'][$product_id] += 1;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
    }

    // Redirect to cart page
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Collection | Old Money Fashion</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
<style>
body { margin: 0; font-family: 'Montserrat', sans-serif; animation: gradientShift 15s ease infinite; background: linear-gradient(270deg,#0c0c0c,#2e2e2e,#a38d5a,#d9cbb5); background-size: 800% 800%; color: #fff; }
@keyframes gradientShift {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}
.navbar { display:flex; justify-content:space-between; align-items:center; padding:20px 60px; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); position:sticky; top:0; z-index:100; }
.navbar .logo { font-family:'Playfair Display', serif; font-size:1.8em; font-weight:600; color:#d9cbb5; }
.navbar ul { list-style:none; display:flex; gap:25px; margin:0; }
.navbar a { text-decoration:none; color:#fff; font-weight:400; transition:color 0.3s ease; }
.navbar a:hover,.navbar a.active { color:#d9cbb5; }
.user-info { font-size:0.9em; color:#d9cbb5; margin-top:5px; }
.container { max-width:1200px; margin:60px auto; padding:20px; }
h1 { text-align:center; font-family:'Playfair Display', serif; color:#d9cbb5; margin-bottom:40px; }
.product-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(260px,1fr)); gap:30px; justify-items:center; }
.product-card { background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.3); text-align:center; transition: transform 0.3s ease, box-shadow 0.3s ease; }
.product-card:hover { transform: translateY(-8px); box-shadow:0 8px 30px rgba(0,0,0,0.5); }
.product-card img { width:100%; height:250px; object-fit:cover; }
.product-card h3 { font-family:'Playfair Display', serif; font-size:1.2em; color:#d9cbb5; margin:15px 0 5px 0; }
.product-card p { color:#ddd; font-size:0.95em; padding:0 15px; height:45px; overflow:hidden; }
.price { font-weight:bold; color:#fff; margin:10px 0; }
.btn { background:#d9cbb5; color:#000; padding:10px 18px; border-radius:5px; text-decoration:none; display:inline-block; margin-bottom:15px; transition: background 0.3s ease, transform 0.3s ease; }
.btn:hover { background:#bda87f; transform:translateY(-3px); }
</style>
</head>
<body>

<header class="navbar">
  <div>
    <div class="logo">Old Money Fashion</div>
    <?php if($user_logged_in): ?>
      <div class="user-info"><?php echo htmlspecialchars($user_name); ?> (<?php echo htmlspecialchars($user_email); ?>)</div>
    <?php endif; ?>
  </div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php" class="active">Products</a></li>
      <li><a href="orders.php">view orders</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li><a href="auth.php">Signup</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="contact.php">Contact Us</a></li>
      <li><a href="about.php">About Us</a></li>
    </ul>
  </nav>
</header>

<div class="container">
  <h1>Our Exclusive Collection</h1>
  <div class="product-grid">
  <?php
  $query = "SELECT * FROM products ORDER BY id DESC";
  $result = mysqli_query($conn, $query);

  if(!$result){
      echo "<p style='text-align:center; color:red;'>Database error: ".mysqli_error($conn)."</p>";
  } elseif(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_assoc($result)){
          $image = htmlspecialchars($row['image']);
          $name = htmlspecialchars($row['name']);
          $desc = htmlspecialchars($row['description']);
          $price = htmlspecialchars($row['price']);
          $id = intval($row['id']);

          echo "
          <div class='product-card'>
              <img src='assets/images/products/{$image}' alt='{$name}'>
              <h3>{$name}</h3>
              <p>{$desc}</p>
              <div class='price'>₹{$price}</div>
              <a href='products.php?add={$id}' class='btn'>Add to Cart</a>
          </div>
          ";
      }
  } else {
      echo "<p style='text-align:center; color:#ccc;'>No products available at the moment.</p>";
  }
  ?>
  </div>
</div>

</body>
</html>
