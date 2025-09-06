<?php
include_once(__DIR__ . "/../config/db.php");
$response = ['status'=>'error'];

$qid = intval($_POST['qid'] ?? 0);
$answer_text = $conn->real_escape_string($_POST['answer_text'] ?? '');

$audio_path = null;
if (!empty($_FILES['audio']) && $_FILES['audio']['error'] == UPLOAD_ERR_OK) {
    $uploaddir = realpath(__DIR__ . "/../uploads");
    if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
    $fname = time() . "_" . basename($_FILES['audio']['name']);
    $dest = $uploaddir . "/" . $fname;
    if (move_uploaded_file($_FILES['audio']['tmp_name'], $dest)) {
        $audio_path = "uploads/" . $fname;
    }
}

// Very simple evaluator: give score based on length + keywords
function evaluate_answer($question, $answer) {
    $score = 50;
    $len = strlen($answer);
    $score += min(30, intval($len/10));
    $keywords = ['experience','team','project','learn','improve','skill'];
    $found = 0;
    foreach($keywords as $k) if (stripos($answer, $k)!==false) $found++;
    $score += $found * 5;
    $score = min(100, $score);
    return $score;
}

// fetch question text
$qtext = '';
$r = $conn->query("SELECT question_text FROM questions WHERE id=$qid");
if ($r && $r->num_rows>0) {
    $qtext = $r->fetch_assoc()['question_text'];
}

$score = evaluate_answer($qtext, $answer_text);

$stmt = $conn->prepare("INSERT INTO answers (question_id, answer_text, audio_path, score) VALUES (?, ?, ?, ?)");
$stmt->bind_param('issi', $qid, $answer_text, $audio_path, $score);
$stmt->execute();

$response = ['status'=>'ok', 'score'=>$score, 'audio'=>$audio_path];
header('Content-Type: application/json');
echo json_encode($response);
?>