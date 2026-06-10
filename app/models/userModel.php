<?php

function findByEmail($email)
{
    
    $stmt = Database::getInstance()->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function findById($id)
{
    
    $stmt = Database::getInstance()->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createUser($pseudo, $email, $password)
{
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = Database::getInstance()->prepare("INSERT INTO users (pseudo, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$pseudo, $email, $hash]);
}

function getRanking($limit = 50)
{
    
    $stmt = Database::getInstance()->prepare("SELECT id, pseudo, oignons, games_played FROM users ORDER BY oignons DESC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getTop3()
{
    
    $stmt = Database::getInstance()->query("SELECT id, pseudo, oignons FROM users ORDER BY oignons DESC LIMIT 3");
    return $stmt->fetchAll();
}

function getRankPosition($userId)
{
    
    $stmt = Database::getInstance()->prepare("SELECT COUNT(*) + 1 AS position FROM users WHERE oignons > (SELECT oignons FROM users WHERE id = ?)");
    $stmt->execute([$userId]);
    return $stmt->fetch()['position'] ?? 1;
}

function getUserStats($userId)
{
    
    $stmt = Database::getInstance()->prepare("
        SELECT u.pseudo, u.email, u.oignons, u.games_played, u.created_at,
               COALESCE(AVG(g.correct_answers / g.total_questions * 100), 0) AS win_rate
        FROM users u
        LEFT JOIN games g ON g.user_id = u.id
        WHERE u.id = ?
        GROUP BY u.id
    ");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function addOignons($userId, $oignons)
{
    
    $stmt = Database::getInstance()->prepare("UPDATE users SET oignons = oignons + ?, games_played = games_played + 1 WHERE id = ?");
    return $stmt->execute([$oignons, $userId]);
}

function updatePseudo($userId, $pseudo)
{
    
    $stmt = Database::getInstance()->prepare("UPDATE users SET pseudo = ? WHERE id = ?");
    return $stmt->execute([$pseudo, $userId]);
}

function updateEmail($userId, $email)
{
    
    $stmt = Database::getInstance()->prepare("UPDATE users SET email = ? WHERE id = ?");
    return $stmt->execute([$email, $userId]);
}

function updatePassword($userId, $newPassword)
{
    
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = Database::getInstance()->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([$hash, $userId]);
}

function deleteUser($userId)
{
    
    $stmt = Database::getInstance()->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$userId]);
}

function getAllUsers()
{
    $stmt = Database::getInstance()->query("
        SELECT id, pseudo, email, oignons, games_played, is_admin, created_at
        FROM users ORDER BY created_at DESC
    ");
    return $stmt->fetchAll();
}

function deleteUserById($id)
{
    $stmt = Database::getInstance()->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}

function toggleAdmin($id, $isAdmin)
{
    $stmt = Database::getInstance()->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
    return $stmt->execute([$isAdmin ? 1 : 0, $id]);
}
