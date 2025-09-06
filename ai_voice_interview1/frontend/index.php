<?php
include_once(__DIR__ . "/../config/db.php");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>AI Voice Interview - Localhost</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>AI Voice Interview</h1>
  <div class="container">
    <div class="left">
      <h2>Interview</h2>
      <div id="questionBox"><?php
        $q = $conn->query("SELECT * FROM questions ORDER BY id LIMIT 1");
        if ($q && $q->num_rows>0) {
            $row = $q->fetch_assoc();
            echo '<p id="qtext">'.htmlspecialchars($row['question_text']).'</p>';
            echo '<input type="hidden" id="qid" value="'.$row['id'].'" />';
        } else {
            echo '<p>No questions found. Add some in Admin → Questions.</p>';
        }
      ?></div>

      <button id="ttsBtn">Read Question (TTS)</button>
      <div>
        <button id="startRec">Start Recording</button>
        <button id="stopRec" disabled>Stop Recording</button>
      </div>
      <div>
        <textarea id="answerText" placeholder="Or type your answer here..." rows="5" cols="60"></textarea>
      </div>
      <div>
        <button id="submitBtn">Submit Answer</button>
      </div>
      <div id="status"></div>
    </div>

    <div class="right">
      <h2>Quick Links</h2>
      <ul>
        <li><a href="/modules/questions.php">Manage Questions</a></li>
        <li><a href="/modules/report.php">View Reports</a></li>
        <li><a href="/modules/admin.php">Admin Dashboard</a></li>
      </ul>
    </div>
  </div>

<script>
let mediaRecorder;
let audioChunks = [];
let audioBlob = null;
const startBtn = document.getElementById('startRec');
const stopBtn = document.getElementById('stopRec');
const submitBtn = document.getElementById('submitBtn');
const statusDiv = document.getElementById('status');
const ttsBtn = document.getElementById('ttsBtn');

ttsBtn.addEventListener('click', ()=> {
  const text = document.getElementById('qtext').innerText;
  if ('speechSynthesis' in window) {
    const u = new SpeechSynthesisUtterance(text);
    window.speechSynthesis.speak(u);
  } else {
    alert('TTS not supported in this browser.');
  }
});

startBtn.addEventListener('click', async ()=> {
  audioChunks = [];
  const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
  mediaRecorder = new MediaRecorder(stream);
  mediaRecorder.start();
  startBtn.disabled = true;
  stopBtn.disabled = false;
  statusDiv.innerText = 'Recording...';
  mediaRecorder.addEventListener('dataavailable', event => {
    audioChunks.push(event.data);
  });
  mediaRecorder.addEventListener('stop', ()=> {
    audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
    statusDiv.innerText = 'Recording ready to submit.';
  });
});

stopBtn.addEventListener('click', ()=> {
  if (mediaRecorder) {
    mediaRecorder.stop();
    startBtn.disabled = false;
    stopBtn.disabled = true;
  }
});

submitBtn.addEventListener('click', async ()=> {
  const qid = document.getElementById('qid').value;
  const text = document.getElementById('answerText').value;
  const fd = new FormData();
  fd.append('qid', qid);
  fd.append('answer_text', text);
  if (audioBlob) {
    fd.append('audio', audioBlob, 'answer.webm');
  }
  statusDiv.innerText = 'Submitting...';
  const res = await fetch('/modules/save_answer.php', { method: 'POST', body: fd });
  const data = await res.json();
  if (data.status === 'ok') {
    statusDiv.innerText = 'Saved. Score: ' + data.score;
    // load next question (simple)
    window.location.reload();
  } else {
    statusDiv.innerText = 'Error: ' + (data.error || 'unknown');
  }
});
</script>
</body>
</html>
