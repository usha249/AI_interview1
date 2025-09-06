<?php
include_once(__DIR__ . "/../config/db.php");
$qc = $conn->query("SELECT COUNT(*) as c FROM questions")->fetch_assoc()['c'];
$ac = $conn->query("SELECT COUNT(*) as c FROM answers")->fetch_assoc()['c'];
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin</title></head><body>
  <h1>Admin Dashboard</h1>
  <ul>
    <li>Total Questions: <?= $qc ?></li>
    <li>Total Answers: <?= $ac ?></li>
  </ul>
  <p><a href="/modules/questions.php">Manage Questions</a></p>
  <p><a href="/modules/report.php">View Reports</a></p>
  <p><a href="/frontend/index.php">Open Interview</a></p>
</body></html>
