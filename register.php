<?php
include 'connect.php';
$msg = "";
$msg_color = "var(--danger)";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $msg = "Please fill all fields.";
    } else {
       
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "Username already taken.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $ins->bind_param("ss", $username, $hash);
            if ($ins->execute()) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $msg = "DB error: " . $conn->error;
            }
            $ins->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Create Account</h1>
      <div class="links"><a href="login.php">Login</a></div>
    </div>

    <div class="form-card">
      <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Create username" required>
        <input type="password" name="password" placeholder="Create password" required>
        <button type="submit">Register</button>
      </form>

      <?php if ($msg): ?>
        <p class="note" style="color: #fff; margin-top:12px;"><?php echo htmlspecialchars($msg); ?></p>
      <?php else: ?>
        <p class="note">Choose a username and password. After registration you can login.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
