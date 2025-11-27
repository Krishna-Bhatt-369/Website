<?php
include 'connect.php';
session_start();
$msg = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $msg = "Please fill both fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hash, $is_admin);
            $stmt->fetch();
            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user'] = $username;
                $_SESSION['is_admin'] = (int)$is_admin;
                header("Location: shayri.php");
                exit;
            } else {
                $msg = "Wrong username or password.";
            }
        } else {
            $msg = "Wrong username or password.";
        }
        $stmt->close();
    }
}
$registeredMsg = isset($_GET['registered']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Login</h1>
      <div class="links"><a href="register.php">Register</a></div>
    </div>

    <div class="form-card">
      <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Enter username" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit" name="login">Login</button>
      </form>

      <?php if ($registeredMsg): ?>
        <p class="note" style="color:var(--accent)">Registration successful — please login.</p>
      <?php endif; ?>

      <?php if ($msg): ?>
        <p class="note" style="color: #ff9999;"><?php echo htmlspecialchars($msg); ?></p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
