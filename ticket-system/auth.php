<?php 

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function require_login() : void {
  if (empty($_SESSION['user'])) {
    header('Location: /public/index.php');
    exit;
  }
}

function current_user() : ?array {
  return $_SESSION['user'] ?? null;
}

function is_staff() : bool {
  $u = current_user();
  if (!$u) return false;
  return in_array($u['role'], ['agent','admin'], true);
}

function json_response($data, int $code = 200) : void {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data);
  exit;
}
