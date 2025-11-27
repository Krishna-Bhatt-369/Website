<?php
include 'connect.php';
session_start();
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
$uid = $_SESSION['user_id'];

$msg = "";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])){
    $new = $_POST['new_password'];
    if($new === ''){
        $msg = "Password cannot be empty.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $up = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $up->bind_param("si", $hash, $uid);
        if($up->execute()){
            $msg = "Password updated successfully.";
        } else {
            $msg = "Database error.";
        }
        $up->close();
    }
}

$stmt = $conn->prepare("SELECT username, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$stmt->bind_result($username, $created);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Profile</h1>
      <div class="links">
        <a href="shayri.php">Home</a>
        <a href="logout.php" style="color:var(--danger); margin-left:10px;">Logout</a>
      </div>
    </div>

    <div class="box">
      <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
      <p><strong>Member since:</strong> <?php echo htmlspecialchars($created); ?></p>
    </div>

    <div class="form-card" style="max-width:480px;">
      <h3 style="color:var(--accent)">Change Password</h3>
      <form method="post">
        <input type="password" name="new_password" placeholder="New password" required>
        <button type="submit">Update Password</button>
      </form>
      <?php if($msg): ?><p class="note" style="color:#fff;"><?php echo htmlspecialchars($msg); ?></p><?php endif; ?>
    </div>
  </div>
</body>
</html>
