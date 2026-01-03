<?php
require_once __DIR__ . '/../auth.php';

if (!empty($_SESSION['user'])) {
  header('Location: /public/dashboard.php');
  exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = db()->prepare("SELECT id,name,email,password_hash,role FROM users WHERE email=?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($password, $user['password_hash'])) {
    $error = "Invalid email or password.";
  } else {
    unset($user['password_hash']);
    $_SESSION['user'] = $user;
    header('Location: /public/dashboard.php');
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Ticket System - Login</title>
  <link rel="stylesheet" href="/public/assets/styles.css">
</head>
<body>
  <div class="container">
    <h1>Ticket Service</h1>

    <?php if ($error): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="card">
      <label>Email</label>
      <input name="email" type="email" required>

      <label>Password</label>
      <input name="password" type="password" required>

      <button class="btn" type="submit">Login</button>
      <p class="muted">No account? <a href="/public/register.php">Register</a></p>
    </form>
  </div>
</body>
</html>
