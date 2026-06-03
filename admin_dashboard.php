<?php 
session_start(); 
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit; 
} 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            animation: gradientShift 15s ease infinite;
            background: linear-gradient(270deg, #0c0c0c, #2e2e2e, #a38d5a, #d9cbb5);
            background-size: 800% 800%;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            text-align: center;
        }
        h1 {
            color: #d9cbb5;
            font-family: 'Playfair Display', serif;
            margin-bottom: 50px;
        }
        .btn {
            display: inline-block;
            padding: 20px 40px;
            margin: 20px;
            font-size: 1.1em;
            font-weight: 500;
            color: #fff;
            background: linear-gradient(135deg, #d9cbb5, #a38d5a);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.5);
            background: linear-gradient(135deg, #bda87f, #8c7350);
        }
        .btn:active {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="btn-container">
            <a href="manage_users.php" class="btn">Manage Users</a>
            <a href="manage_products.php" class="btn">Manage Products</a>
            <a href="manage_contact.php" class="btn">Contact Messages</a>
            <a href="manage_feedback.php" class="btn">Feedback Messages</a>
            <a href="admin_logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>
