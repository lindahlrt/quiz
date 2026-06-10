<?php

function saveGame($userId, $correctAnswers, $totalQuestions, $oignonsEarned)
{
    
    $stmt = Database::getInstance()->prepare("
        INSERT INTO games (user_id, score, total_questions, correct_answers, oignons_earned)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $correctAnswers, $totalQuestions, $correctAnswers, $oignonsEarned]);
    return (int)Database::getInstance()->lastInsertId();
}

function saveGameQuestion($gameId, $questionId, $userAnswer, $isCorrect)
{
    
    $stmt = Database::getInstance()->prepare("
        INSERT INTO game_questions (game_id, question_id, user_answer, is_correct)
        VALUES (?, ?, ?, ?)
    ");
    return $stmt->execute([$gameId, $questionId, $userAnswer, $isCorrect ? 1 : 0]);
}

function getGameHistory($userId, $limit = 10)
{
    
    $stmt = Database::getInstance()->prepare("
        SELECT * FROM games WHERE user_id = ?
        ORDER BY played_at DESC LIMIT ?
    ");
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getGameQuestions($gameId)
{
    
    $stmt = Database::getInstance()->prepare("
        SELECT gq.*, q.question, q.answer1, q.answer2, q.answer3, q.answer4, q.correct_answer
        FROM game_questions gq
        JOIN questions q ON q.id = gq.question_id
        WHERE gq.game_id = ?
        ORDER BY gq.answered_at ASC
    ");
    $stmt->execute([$gameId]);
    return $stmt->fetchAll();
}

function getTrainingSessions($userId)
{
    
    $stmt = Database::getInstance()->prepare("SELECT COUNT(*) AS sessions FROM games WHERE user_id = ?");
    $stmt->execute([$userId]);
    return (int)$stmt->fetch()['sessions'];
}
