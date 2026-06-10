<?php

function countQuestions()
{
    
    $stmt = Database::getInstance()->query("SELECT COUNT(*) AS total FROM questions");
    return (int)$stmt->fetch()['total'];
}

function getRandomQuestions($limit = 50)
{
    
    $stmt = Database::getInstance()->prepare("SELECT * FROM questions ORDER BY RAND() LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getQuestionById($id)
{
    
    $stmt = Database::getInstance()->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAllQuestions()
{
    
    $stmt = Database::getInstance()->query("SELECT * FROM questions ORDER BY id ASC");
    return $stmt->fetchAll();
}

function addQuestion($question, $a1, $a2, $a3, $a4, $correct)
{
    
    $stmt = Database::getInstance()->prepare("
        INSERT INTO questions (question, answer1, answer2, answer3, answer4, correct_answer)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$question, $a1, $a2, $a3, $a4, $correct]);
}

function updateQuestion($id, $question, $a1, $a2, $a3, $a4, $correct)
{
    
    $stmt = Database::getInstance()->prepare("
        UPDATE questions
        SET question=?, answer1=?, answer2=?, answer3=?, answer4=?, correct_answer=?
        WHERE id=?
    ");
    return $stmt->execute([$question, $a1, $a2, $a3, $a4, $correct, $id]);
}

function deleteQuestion($id)
{
    
    $stmt = Database::getInstance()->prepare("DELETE FROM questions WHERE id = ?");
    return $stmt->execute([$id]);
}
