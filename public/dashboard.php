<?php
require_once __DIR__ . '/../auth.php';
require_login();
$u = current_user();
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="/public/assets/styles.css">
</head>
<body>
  <div class="topbar">
    <div class="brand">Ticket Service</div>
    <div class="right">
      <span class="pill"><?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['role']) ?>)</span>
      <a class="link" href="/logout.php">Logout</a>
    </div>
  </div>
  <div class="container">
    <div class="grid">
      <div class="card">
        <h2>Create Ticket</h2>
        <form id="createTicketForm">
          <label>Title</label>
          <input name="title" required minlength="3" />

          <label>Description</label>
          <textarea name="description" required minlength="5"></textarea>

          <label>Priority</label>
          <select name="priority">
            <option value="low">low</option>
            <option value="medium" selected>medium</option>
            <option value="high">high</option>
            <option value="urgent">urgent</option>
          </select>

          <button class="btn" type="submit">Submit</button>
          <div id="createMsg" class="muted"></div>
        </form>
      </div>

      <div class="card">
        <div class="row">
          <h2>My Tickets</h2>
          <button class="btn secondary" id="refreshBtn" type="button">Refresh</button>
        </div>

        <div class="filters">
          <select id="statusFilter">
            <option value="">All status</option>
            <option value="open">open</option>
            <option value="in_progress">in_progress</option>
            <option value="resolved">resolved</option>
            <option value="closed">closed</option>
          </select>

          <input id="qFilter" placeholder="Search title/description..." />
        </div>

        <div id="ticketsList" class="list"></div>
      </div>
    </div>
  </div>

  <script src="/public/assets/app.js"></script>
</body>
</html>
