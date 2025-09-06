<?php
include_once(__DIR__ . "/../config/db.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $q = $conn->real_escape_string($_POST['question_text']);
        $cat = $conn->real_escape_string($_POST['category'] ?? 'general');
        $conn->query("INSERT INTO questions (question_text, category) VALUES ('$q', '$cat')");
        header('Location: /modules/questions.php');
        exit;
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM questions WHERE id=$id");
        header('Location: /modules/questions.php');
        exit;
    } elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $q = $conn->real_escape_string($_POST['question_text']);
        $cat = $conn->real_escape_string($_POST['category'] ?? 'general');
        $conn->query("UPDATE questions SET question_text='$q', category='$cat' WHERE id=$id");
        header('Location: /modules/questions.php');
        exit;
    }
}

$res = $conn->query("SELECT * FROM questions ORDER BY id DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Manage Questions</title></head>
<body>
  <h1>Manage Questions</h1>
  <form method="POST" style="margin-bottom:20px">
    <input type="hidden" name="action" value="add" />
    <div>
      <label>Question text</label><br>
      <textarea name="question_text" rows="3" cols="60" required></textarea>
    </div>
    <div>
      <label>Category</label><br>
      <input name="category" value="general" />
    </div>
    <div><button type="submit">Add Question</button></div>
  </form>

  <h2>Existing Questions</h2>
  <table border="1" cellpadding="6" cellspacing="0">
    <tr><th>ID</th><th>Text</th><th>Category</th><th>Actions</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['question_text']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td>
          <form method="POST" style="display:inline">
            <input type="hidden" name="action" value="delete" />
            <input type="hidden" name="id" value="<?= $row['id'] ?>" />
            <button type="submit">Delete</button>
          </form>
          <button onclick="edit(<?= $row['id'] ?>, <?= htmlspecialchars(json_encode($row['question_text'])) ?>, '<?= $row['category'] ?>')">Edit</button>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div id="editBox" style="display:none; margin-top:20px;">
    <h3>Edit Question</h3>
    <form method="POST">
      <input type="hidden" name="action" value="edit" />
      <input type="hidden" name="id" id="edit_id" />
      <div><textarea name="question_text" id="edit_text" rows="3" cols="60"></textarea></div>
      <div>Category: <input name="category" id="edit_cat" /></div>
      <div><button type="submit">Save</button></div>
    </form>
  </div>

<script>
function edit(id, text, cat){
  document.getElementById('editBox').style.display='block';
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_text').value = text;
  document.getElementById('edit_cat').value = cat;
  window.scrollTo(0,document.body.scrollHeight);
}
</script>
</body></html>
