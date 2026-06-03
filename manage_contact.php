<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin_id'])) header("Location: admin_login.php");

// ✅ Try fetching contact messages safely
$query = "SELECT * FROM contact_us ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// ✅ Check if the query worked
if (!$result) {
    die("<div style='color:red; text-align:center; margin-top:50px;'>Error fetching contact messages: " . mysqli_error($conn) . "</div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Messages | Admin</title>
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
h2 {
    font-family: 'Playfair Display', serif;
    color: #d9cbb5;
    margin-bottom: 20px;
}
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
    color: #d9cbb5; 
    font-weight: 600; 
}
.navbar a.logout { 
    color: #fff; 
    text-decoration: none; 
    font-weight: 500; 
}
.navbar a.logout:hover { text-decoration: underline; }
a.back { 
    display: inline-block; 
    margin-bottom: 20px; 
    color: #d9cbb5; 
    text-decoration: none; 
}
a.back:hover { text-decoration: underline; }
</style>
</head>
<body>

<header class="navbar">
    <div class="logo">Admin Panel</div>
    <a href="admin_logout.php" class="logout">Logout</a>
</header>

<div class="container">
<h2>Contact Messages</h2>
<a href="admin_dashboard.php" class="back">← Back to Dashboard</a>

<?php if (mysqli_num_rows($result) > 0): ?>
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Message</th>
    <th>Created At</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['email']); ?></td>
    <td><?php echo htmlspecialchars($row['message']); ?></td>
    <td><?php echo $row['created_at']; ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p style="text-align:center; color:#d9cbb5;">No contact messages found.</p>
<?php endif; ?>
</div>

</body>
</html>
