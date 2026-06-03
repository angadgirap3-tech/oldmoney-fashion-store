<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin_id'])) header("Location: admin_login.php");

$id = intval($_GET['id']);
$product = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM products WHERE id=$id"));

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    if($image){
        move_uploaded_file($_FILES['image']['tmp_name'], "assets/images/products/".$image);
        $sql = "UPDATE products SET name='$name', description='$desc', price='$price', image='$image' WHERE id=$id";
    } else {
        $sql = "UPDATE products SET name='$name', description='$desc', price='$price' WHERE id=$id";
    }
    mysqli_query($conn,$sql);
    header("Location: manage_products.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    animation: gradientShift 15s ease infinite;
    background: linear-gradient(270deg, #0c0c0c, #2e2e2e, #a38d5a, #d9cbb5);
    background-size: 800% 800%;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.container {
    background: rgba(0,0,0,0.7);
    padding: 30px;
    border-radius: 12px;
    max-width: 500px;
    width: 100%;
    text-align: center;
}
h2 {
    font-family: 'Playfair Display', serif;
    color: #d9cbb5;
    margin-bottom: 20px;
}
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
input[type=text],
input[type=number],
textarea,
input[type=file] {
    padding: 10px;
    border-radius: 6px;
    border: none;
    font-family: 'Montserrat', sans-serif;
    width: 100%;
}
textarea {
    resize: vertical;
}
button {
    background: #d9cbb5;
    color: #000;
    padding: 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 1em;
    transition: background 0.3s ease;
}
button:hover {
    background: #bda87f;
}
a.back {
    display: inline-block;
    margin-bottom: 15px;
    color: #d9cbb5;
    text-decoration: none;
}
a.back:hover {
    text-decoration: underline;
}
img {
    max-width: 100px;
    margin: 10px 0;
    border-radius: 5px;
}
</style>
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <a href="manage_products.php" class="back">← Back to Products</a>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" placeholder="Product Name" required>
        <textarea name="description" placeholder="Product Description"><?php echo htmlspecialchars($product['description']); ?></textarea>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="Price" required>
        <?php if($product['image']): ?>
            <div>Current Image:</div>
            <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
        <?php endif; ?>
        <input type="file" name="image">
        <button type="submit" name="update">Update Product</button>
    </form>
</div>
</body>
</html>
