<?php
include_once(__DIR__ . "/../config/db.php");
$res = $conn->query("SELECT a.*, q.question_text FROM answers a LEFT JOIN questions q ON a.question_id=q.id ORDER BY a.created_at DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Reports</title></head>
<body>
  <h1>Interview Reports</h1>
  <table border="1" cellpadding="6" cellspacing="0">
    <tr><th>ID</th><th>Question</th><th>Answer Text</th><th>Audio</th><th>Score</th><th>Time</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['question_text']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['answer_text'])) ?></td>
        <td>
          <?php if ($row['audio_path']): ?>
            <audio controls src="/<?= $row['audio_path'] ?>"></audio>
          <?php endif; ?>
        </td>
        <td><?= $row['score'] ?></td>
        <td><?= $row['created_at'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <p><a href="/frontend/index.php">Back to Interview</a></p>
</body></html>
