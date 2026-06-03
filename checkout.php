<?php
include('db.php');
session_start();

// Check if user is logged in
$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user'] ?? 'Guest';
$user_email = $_SESSION['email'] ?? '';

// Get cart items
$cart_items = [];
if($user_logged_in){
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT c.id as cart_id, p.id as product_id, p.name, p.price, c.quantity 
                   FROM cart c 
                   JOIN products p ON c.product_id = p.id 
                   WHERE c.user_id = '$user_id'";
    $cart_result = mysqli_query($conn, $cart_query);
    if(mysqli_num_rows($cart_result) > 0){
        while($row = mysqli_fetch_assoc($cart_result)){
            $cart_items[] = $row;
        }
    }
} else {
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        $product_ids = array_keys($_SESSION['cart']);
        $ids = implode(',', $product_ids);
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $cart_items[] = [
                    'product_id' => $row['id'],
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'quantity' => $_SESSION['cart'][$row['id']]
                ];
            }
        }
    }
}

// Handle order placement
$success_message = "";
$error_message = "";
if(isset($_POST['place_order']) && !empty($cart_items)){
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city_zip = mysqli_real_escape_string($conn, $_POST['city_zip']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $grand_total = 0;

    foreach($cart_items as $item){
        $grand_total += $item['price'] * $item['quantity'];
    }

    if($user_logged_in){
        // Insert order into database
        $order_sql = "INSERT INTO orders (user_id, full_name, address, city_zip, payment_method, total_amount, created_at) 
                      VALUES ('$user_id','$full_name','$address','$city_zip','$payment_method','$grand_total', NOW())";
        if(mysqli_query($conn, $order_sql)){
            $order_id = mysqli_insert_id($conn);
            foreach($cart_items as $item){
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                $price = $item['price'];
                mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                     VALUES ('$order_id','$pid','$qty','$price')");
            }
            // Clear user's cart
            mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
            $success_message = "Your order has been placed successfully!";
        } else {
            $error_message = "Order could not be placed: " . mysqli_error($conn);
        }
    } else {
        // Guest: just clear session cart
        unset($_SESSION['cart']);
        $success_message = "Your order has been placed successfully! (Guest order, not saved in DB)";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout | Old Money Fashion</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family:'Montserrat',sans-serif; animation: gradientShift 15s ease infinite; background: linear-gradient(270deg,#0c0c0c,#2e2e2e,#a38d5a,#d9cbb5); background-size:800% 800%; color:#fff; }
@keyframes gradientShift {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:20px 60px;background:rgba(0,0,0,0.6);backdrop-filter:blur(6px);position:sticky;top:0;z-index:100;}
.navbar .logo{font-family:'Playfair Display', serif;font-size:1.8em;font-weight:600;color:#d9cbb5;}
.navbar ul{list-style:none;display:flex;gap:25px;margin:0;}
.navbar a{text-decoration:none;color:#fff;font-weight:400;transition:color 0.3s ease;}
.navbar a:hover,.navbar a.active{color:#d9cbb5;}
.user-info{font-size:0.9em;color:#d9cbb5;}
.container{max-width:1000px;margin:80px auto;padding:20px;text-align:center;}
h1{font-family:'Playfair Display', serif;color:#d9cbb5;margin-bottom:30px;}
table{width:100%;border-collapse:collapse;margin-bottom:30px;background:rgba(255,255,255,0.05);}
th, td{padding:15px;border-bottom:1px solid rgba(255,255,255,0.1);}
th{color:#d9cbb5;}
.btn{background:#d9cbb5;color:#000;padding:10px 20px;border-radius:4px;text-decoration:none;transition:background 0.3s;}
.btn:hover{background:#bda87f; cursor:pointer;}
.address-payment{margin-top:30px;display:flex;flex-direction:column;gap:15px;text-align:left;max-width:500px;margin-left:auto;margin-right:auto;}
.address-payment input,.address-payment select{padding:10px;border-radius:4px;border:none;}
.message{margin:20px 0;padding:15px;border-radius:5px;}
.success{background-color:rgba(29, 185, 84,0.8);color:#fff;}
.error{background-color:rgba(200,50,50,0.8);color:#fff;}
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
      <li><a href="products.php">Products</a></li>
      <li><a href="checkout.php" class="active">Checkout</a></li>
      <li><a href="cart.php">Cart</a></li>
    </ul>
  </nav>
</header>

<div class="container">
<h1>Checkout</h1>

<?php if($success_message) echo "<div class='message success'>{$success_message}</div>"; ?>
<?php if($error_message) echo "<div class='message error'>{$error_message}</div>"; ?>

<?php if(empty($cart_items)) { ?>
    <p>Your cart is empty.</p>
<?php } else { ?>
    <table>
        <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
        <?php 
        $grand_total = 0;
        foreach($cart_items as $item){
            $total = $item['price'] * $item['quantity'];
            $grand_total += $total;
        ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td>₹<?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₹<?php echo $total; ?></td>
        </tr>
        <?php } ?>
    </table>
    <h3>Grand Total: ₹<?php echo $grand_total; ?></h3>

    <form method="post">
        <div class="address-payment">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="address" placeholder="Delivery Address" required>
            <input type="text" name="city_zip" placeholder="City, State, ZIP" required>
            <select name="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="cod">Cash on Delivery</option>
                <option value="card">Credit/Debit Card</option>
                <option value="upi">UPI</option>
            </select>
        </div>
        <button type="submit" name="place_order" class="btn">Place Order</button>
    </form>
<?php } ?>
</div>

</body>
</html>
