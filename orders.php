<?php
include('db.php');
session_start();

// 🚫 Restrict access if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?redirect=orders");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user'] ?? 'User';
$user_email = $_SESSION['email'] ?? '';

// Fetch user's orders
$order_query = "
    SELECT o.id AS order_id, o.total_price, o.status, o.created_at
    FROM orders o
    WHERE o.user_id = '$user_id'
    ORDER BY o.created_at DESC
";
$order_result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Orders | Old Money Fashion</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
<style>
body {
  margin:0; font-family:'Montserrat',sans-serif;
  background: linear-gradient(270deg,#0c0c0c,#2e2e2e,#a38d5a,#d9cbb5);
  background-size:800% 800%; color:#fff;
  animation: gradientShift 15s ease infinite;
}
@keyframes gradientShift {
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}
.navbar {
  display:flex; justify-content:space-between; align-items:center;
  padding:20px 60px; background:rgba(0,0,0,0.6);
  backdrop-filter:blur(6px); position:sticky; top:0; z-index:100;
}
.navbar .logo { font-family:'Playfair Display', serif; font-size:1.8em; font-weight:600; color:#d9cbb5; }
.navbar ul { list-style:none; display:flex; gap:25px; margin:0; }
.navbar a { text-decoration:none; color:#fff; transition:color 0.3s ease; }
.navbar a:hover, .navbar a.active { color:#d9cbb5; }
.container { max-width:900px; margin:80px auto; padding:20px; text-align:center; }
h1 { font-family:'Playfair Display', serif; color:#d9cbb5; margin-bottom:30px; }
table { width:100%; border-collapse:collapse; background:rgba(255,255,255,0.05); margin-bottom:30px; }
th, td { padding:12px; border-bottom:1px solid rgba(255,255,255,0.1); text-align:center; }
th { color:#d9cbb5; }
.status { padding:5px 10px; border-radius:6px; }
.status.Pending { background:rgba(255,255,0,0.2); color:#ffd700; }
.status.Shipped { background:rgba(0,128,255,0.2); color:#4da6ff; }
.status.Delivered { background:rgba(0,255,0,0.2); color:#66ff66; }
.details { background:rgba(255,255,255,0.07); margin:15px 0; border-radius:10px; padding:10px 0; }
</style>
</head>
<body>

<header class="navbar">
  <div>
    <div class="logo">Old Money Fashion</div>
    <div class="user-info"><?php echo htmlspecialchars($user_name); ?> (<?php echo htmlspecialchars($user_email); ?>)</div>
  </div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li><a href="orders.php" class="active">My Orders</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
</header>

<div class="container">
  <h1>🧾 My Orders</h1>

  <?php if (mysqli_num_rows($order_result) > 0): ?>
    <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
      <div class="details">
        <h3>Order #<?php echo $order['order_id']; ?> — 
            <span class="status <?php echo $order['status']; ?>"><?php echo $order['status']; ?></span></h3>
        <p><small>Placed on: <?php echo $order['created_at']; ?></small></p>

        <table>
          <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr>
          <?php
          $order_id = $order['order_id'];
          $items_result = mysqli_query($conn, "
            SELECT p.name, oi.price, oi.quantity
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = '$order_id'
          ");
          $order_total = 0;
          while ($item = mysqli_fetch_assoc($items_result)):
            $total = $item['price'] * $item['quantity'];
            $order_total += $total;
          ?>
          <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td>₹<?php echo number_format($item['price']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₹<?php echo number_format($total); ?></td>
          </tr>
          <?php endwhile; ?>
          <tr><th colspan="3" style="text-align:right;">Grand Total:</th>
              <th>₹<?php echo number_format($order_total); ?></th></tr>
        </table>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No orders found. <a href="products.php" style="color:#d9cbb5;">Start shopping</a></p>
  <?php endif; ?>
</div>

</body>
</html>
