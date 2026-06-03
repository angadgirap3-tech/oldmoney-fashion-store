<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin_id'])) header("Location: admin_login.php");

// Handle add/edit/delete
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    if($image){
        move_uploaded_file($_FILES['image']['tmp_name'], "assets/images/products/".$image);
    }
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?,?,?,?)");
    $stmt->bind_param("ssds",$name,$desc,$price,$image);
    $stmt->execute();
    header("Location: manage_products.php");
    exit;
}

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM products WHERE id=$id");
    header("Location: manage_products.php");
    exit;
}

// Fetch products
$result = mysqli_query($conn,"SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Products | Admin</title>
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
.container {
    max-width: 1000px;
    margin: 50px auto;
    padding: 20px;
    background: rgba(0,0,0,0.6);
    border-radius: 10px;
}
h2, h3 {
    font-family: 'Playfair Display', serif;
    color: #d9cbb5;
}
form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 30px;
}
input, textarea {
    padding: 10px;
    border-radius: 5px;
    border: none;
    font-family: 'Montserrat', sans-serif;
}
button, .btn-dashboard {
    background: #d9cbb5;
    color: #000;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    border: none;
    transition: background 0.3s ease;
    text-decoration: none;
    display: inline-block;
}
button:hover, .btn-dashboard:hover { background: #bda87f; }
table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255,255,255,0.05);
}
th, td {
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    text-align: center;
}
th {
    color: #d9cbb5;
}
img {
    max-width: 50px;
    border-radius: 5px;
}
a {
    color: #d9cbb5;
    text-decoration: none;
}
a:hover { text-decoration: underline; }
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
.navbar .logo { font-family: 'Playfair Display', serif; font-size: 1.8em; color: #d9cbb5; font-weight: 600; }
.navbar a.logout { color: #fff; }
.btn-container {
    margin-bottom: 20px;
}
</style>
</head>
<body>

<header class="navbar">
    <div class="logo">Admin Panel</div>
    <a href="admin_logout.php" class="logout">Logout</a>
</header>

<div class="container">
<div class="btn-container">
    <a href="admin_dashboard.php" class="btn-dashboard">Back to Dashboard</a>
</div>

<h2>Manage Products</h2>

<h3>Add New Product</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Product Description"></textarea>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="file" name="image">
    <button type="submit" name="add">Add Product</button>
</form>

<h3>All Products</h3>
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Image</th>
    <th>Actions</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?php echo $row['id'];?></td>
    <td><?php echo $row['name'];?></td>
    <td>₹<?php echo $row['price'];?></td>
    <td>
        <?php if($row['image']): ?>
        <img src="assets/images/products/<?php echo $row['image'];?>" alt="<?php echo $row['name'];?>">
        <?php endif; ?>
    </td>
    <td>
        <a href="edit_product.php?id=<?php echo $row['id'];?>">Edit</a> |
        <a href="manage_products.php?delete=<?php echo $row['id'];?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

</body>
</html>
