<?php
include('db.php');
session_start();

// 🚫 Restrict access if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?redirect=cart");
    exit;
}

// User info
$user_logged_in = true;
$user_name = $_SESSION['user'] ?? 'User';
$user_email = $_SESSION['email'] ?? '';
$user_id = $_SESSION['user_id'];

// ✅ Fetch cart items
$cart_items = [];
$cart_query = "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = '$user_id'";
$cart_result = mysqli_query($conn, $cart_query);

if ($cart_result && mysqli_num_rows($cart_result) > 0) {
    while ($row = mysqli_fetch_assoc($cart_result)) {
        $cart_items[] = $row;
    }
} elseif (!$cart_result) {
    error_log("Cart query failed: " . mysqli_error($conn));
}

// ✅ Handle order placement
$recent_order_items = [];
if (isset($_POST['place_order']) && !empty($cart_items)) {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city_zip = mysqli_real_escape_string($conn, $_POST['city_zip']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $grand_total = 0;

    // Update quantities (max 50)
    foreach ($cart_items as &$item) {
        $product_id = $item['product_id'];
        $qty = intval($_POST['quantity'][$product_id] ?? $item['quantity']);
        $qty = max(1, min(50, $qty));
        $item['quantity'] = $qty;

        mysqli_query($conn, "UPDATE cart SET quantity='$qty' WHERE user_id='$user_id' AND product_id='$product_id'");

        $grand_total += $item['price'] * $qty;
    }
    unset($item);

    // ✅ Insert order
    $order_sql = "INSERT INTO orders (user_id, total_price, status) 
                  VALUES ('$user_id', '$grand_total', 'Pending')";
    $insert_order = mysqli_query($conn, $order_sql);

    if ($insert_order) {
        $order_id = mysqli_insert_id($conn);

        // ✅ Insert order items
        foreach ($cart_items as $item) {
            $pid = $item['product_id'];
            $qty = $item['quantity'];
            $price = $item['price'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                 VALUES ('$order_id','$pid','$qty','$price')");
        }

        // ✅ Clear cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

        // ✅ Fetch recent order summary
        $recent_order_query = "SELECT p.name, oi.quantity, oi.price 
                               FROM order_items oi 
                               JOIN products p ON oi.product_id = p.id 
                               WHERE oi.order_id = '$order_id'";
        $recent_order_result = mysqli_query($conn, $recent_order_query);

        if ($recent_order_result && mysqli_num_rows($recent_order_result) > 0) {
            while ($row = mysqli_fetch_assoc($recent_order_result)) {
                $recent_order_items[] = $row;
            }
        } elseif (!$recent_order_result) {
            error_log("Order items fetch failed: " . mysqli_error($conn));
        }

        $success_message = "✅ Your order has been placed successfully!";
    } else {
        $success_message = "❌ Error placing order: " . mysqli_error($conn);
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
}
.navbar a {
    text-decoration: none;
    color: #fff;
    font-weight: 400;
    transition: color 0.3s ease;
}
.navbar a:hover, .navbar a.active { color: #d9cbb5; }
.user-info { font-size: 0.9em; color: #d9cbb5; }
.container {
    max-width: 700px;
    margin: 80px auto;
    padding: 20px;
    text-align: center;
}
h1 { font-family: 'Playfair Display', serif; color: #d9cbb5; margin-bottom: 30px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: rgba(255,255,255,0.05); }
th, td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); }
th { color: #d9cbb5; }
input[type=number], input, select {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 4px;
    border: none;
}
.btn {
    background: #d9cbb5;
    color: #000;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    margin-top: 10px;
    display: inline-block;
    cursor: pointer;
    transition: background 0.3s;
}
.btn:hover { background: #bda87f; }
.success { color: #b3ffb3; margin-bottom: 15px; }
.order-summary { margin-top: 30px; }
.order-summary table { background: rgba(255,255,255,0.07); }
.order-summary h2 { color: #d9cbb5; margin-bottom: 15px; }
</style>
</head>
<body>

<header class="navbar">
  <div>
      <div class="logo">Old Money Fashion</div>
      <div class="user-info">
        <?php echo htmlspecialchars($user_name); ?> (<?php echo htmlspecialchars($user_email); ?>)
      </div>
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
<h1>Checkout</h1>

<?php if(isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>

<?php if(!empty($recent_order_items)): ?>
<div class="order-summary">
    <h2>🧾 Recent Order Summary</h2>
    <table>
        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr>
        <?php 
        $recent_total = 0;
        foreach($recent_order_items as $order): 
            $total = $order['price'] * $order['quantity'];
            $recent_total += $total;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($order['name']); ?></td>
            <td>₹<?php echo number_format($order['price']); ?></td>
            <td><?php echo $order['quantity']; ?></td>
            <td>₹<?php echo number_format($total); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr><th colspan="3" style="text-align:right;">Grand Total:</th>
            <th>₹<?php echo number_format($recent_total); ?></th></tr>
    </table>
</div>
<?php endif; ?>

<?php if(empty($cart_items) && empty($recent_order_items)): ?>
    <p>Your cart is empty. <a href="products.php" style="color:#d9cbb5;">Go Shopping</a></p>
<?php elseif(!isset($_POST['place_order'])): ?>
    <form method="POST" id="cartForm">
    <table>
        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr>
        <?php foreach($cart_items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td class="price" data-price="<?php echo $item['price']; ?>">₹<?php echo number_format($item['price']); ?></td>
            <td>
                <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" 
                       value="<?php echo $item['quantity']; ?>" min="1" max="50" 
                       class="qtyInput" data-product-id="<?php echo $item['product_id']; ?>">
            </td>
            <td class="total" id="total-<?php echo $item['product_id']; ?>">₹<?php echo number_format($item['price'] * $item['quantity']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h3>Grand Total: ₹<span id="grandTotal"><?php echo number_format(array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$cart_items))); ?></span></h3>

    <input type="text" name="address" placeholder="Delivery Address" required>
    <input type="text" name="city_zip" placeholder="City, State, ZIP" required>
    <select name="payment_method" required>
        <option value="">Select Payment Method</option>
        <option value="cod">Cash on Delivery</option>
        <option value="upi">UPI</option>
    </select>
    <button type="submit" name="place_order" class="btn">Place Order</button>
    </form>
<?php endif; ?>

</div>

<script>
// ✅ Dynamic total updater
document.querySelectorAll('.qtyInput').forEach(input => {
    input.addEventListener('input', function() {
        let qty = parseInt(this.value);
        qty = Math.min(Math.max(qty, 1), 50);
        this.value = qty;

        const rowPrice = parseFloat(this.closest('tr').querySelector('.price').dataset.price);
        const totalCell = document.getElementById('total-' + this.dataset.productId);
        totalCell.textContent = '₹' + (rowPrice * qty).toFixed(2);

        let grandTotal = 0;
        document.querySelectorAll('.total').forEach(td => {
            grandTotal += parseFloat(td.textContent.replace('₹','')) || 0;
        });
        document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
    });
});
</script>

</body>
</html>
