<?php
include 'connect.php';
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: login.php");
    exit;
}
$msg = "";


if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])){
    $del_id = (int)$_POST['delete_id'];
    
    if($del_id === (int)$_SESSION['user_id']){
        $msg = "You cannot delete your own admin account.";
    } else {
        $del = $conn->prepare("DELETE FROM users WHERE id = ?");
        $del->bind_param("i", $del_id);
        if($del->execute()){
            $msg = "User deleted.";
        } else {
            $msg = "Error deleting user.";
        }
        $del->close();
    }
}

$res = $conn->query("SELECT id, username, is_admin, created_at FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin Panel</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Admin Panel</h1>
      <div class="links">
        <a href="shayri.php">Home</a>
        <a href="logout.php" style="color:var(--danger); margin-left:10px;">Logout</a>
      </div>
    </div>

    <?php if ($msg): ?><p class="note" style="color:#fff;"><?php echo htmlspecialchars($msg); ?></p><?php endif; ?>

    <table class="user-table">
      <thead>
        <tr><th>ID</th><th>Username</th><th>Admin</th><th>Joined</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php while($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo $row['is_admin'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
              <?php if ($row['id'] != $_SESSION['user_id']): ?>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                  <button type="submit" style="background:var(--danger); color:#fff; border:none; padding:6px 8px; border-radius:4px; cursor:pointer;">Delete</button>
                </form>
              <?php else: ?>
                <em>(you)</em>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
