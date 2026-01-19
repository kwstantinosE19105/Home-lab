<?php
require_once __DIR__ . '/../auth.php';

if (!empty($_SESSION['user'])) {
  header('Location: /public/dashboard.php');
  exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if (strlen($name) < 2 || strlen($password) < 8) {
    $error = "Name must be 2+ chars and password 8+ chars.";
  } else {
    try {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = db()->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?, 'customer')");
      $stmt->execute([$name, $email, $hash]);

      // auto login
      $id = (int)db()->lastInsertId();
      $_SESSION['user'] = ['id'=>$id,'name'=>$name,'email'=>$email,'role'=>'customer'];
      header('Location: /public/dashboard.php');
      exit;
    } catch (PDOException $e) {
      if (str_contains($e->getMessage(), 'Duplicate')) $error = "Email already exists.";
      else $error = "Registration error.";
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Register</title>
  <link rel="stylesheet" href="/public/assets/styles.css">
</head>
<body>
  <div class="container">
    <h1>Create account</h1>

    <?php if ($error): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="card">
      <label>Name</label>
      <input name="name" required>

      <label>Email</label>
      <input name="email" type="email" required>

      <label>Password (8+ chars)</label>
      <input name="password" type="password" required>

      <button class="btn" type="submit">Register</button>
      <p class="muted"><a href="/public/index.php">Back to login</a></p>
    </form>
  </div>
</body>
</html>
