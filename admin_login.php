<?php
session_start();
include('db.php');

// Create default admin if not exists
$default_admin_username = 'Angad';
$default_admin_password = 'angad123'; // plain password, will hash
$hashed_password = password_hash($default_admin_password, PASSWORD_DEFAULT);

// Check if admin exists
$check = mysqli_query($conn, "SELECT * FROM admin WHERE username='$default_admin_username'");
if(mysqli_num_rows($check) == 0){
    mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('$default_admin_username', '$hashed_password')");
}

// Handle login
if(isset($_POST['username'], $_POST['password'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1){
        $admin = $result->fetch_assoc();
        if(password_verify($password, $admin['password'])){
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Invalid username!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Old Money Fashion</title>
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
    height: 100vh;
}
@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
form {
    background: rgba(255,255,255,0.05);
    padding: 30px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    gap: 15px;
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
h2 { text-align: center; font-family: 'Playfair Display', serif; color: #d9cbb5; }
</style>
</head>
<body>

<form method="POST">
<h2>Admin Login</h2>
<?php if(isset($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>

</body>
</html>
