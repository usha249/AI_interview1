CREATE DATABASE IF NOT EXISTS ai_voice_interview1;
USE ai_voice_interview1;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE,
  password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_text TEXT NOT NULL,
  category VARCHAR(100) DEFAULT 'general',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS answers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT,
  user_id INT DEFAULT NULL,
  answer_text TEXT,
  audio_path VARCHAR(255),
  score INT DEFAULT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE SET NULL
);

INSERT INTO questions (question_text, category) VALUES
('Tell me about yourself.','general'),
('Why do you want this job?','hr'),
('What are your strengths and weaknesses?','hr');
