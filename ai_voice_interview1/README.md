AI Voice Interview - PHP + MySQL (Localhost)
-------------------------------------------

Place this folder inside your webserver root (e.g., XAMPP htdocs).

1. Import SQL:
   - phpMyAdmin -> import sql/ai_voice_interview.sql
   - or run: mysql < sql/ai_voice_interview.sql

2. Ensure config/db.php credentials match your MySQL setup.

3. Access:
   - Interview UI: http://localhost/ai_voice_interview_12months_php_full/frontend/index.php
   - Manage questions: http://localhost/ai_voice_interview_12months_php_full/modules/questions.php
   - Reports: http://localhost/ai_voice_interview_12months_php_full/modules/report.php
   - Admin: http://localhost/ai_voice_interview_12months_php_full/modules/admin.php

Notes:
- This version uses browser TTS (SpeechSynthesis) and MediaRecorder to capture audio.
- Scoring is a simple PHP keyword/length heuristic. Replace with ML service later.
