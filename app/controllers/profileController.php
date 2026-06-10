<?php

function showProfile()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $user    = findById($_SESSION['user']['id']);
    $stats   = getUserStats($_SESSION['user']['id']);
    $history = getGameHistory($_SESSION['user']['id'], 10);
    $myRank  = getRankPosition($_SESSION['user']['id']);
    $oignons = (int)($stats['oignons'] ?? 0);

    // Calcul de la progression vers le rang supérieur
    $ranking      = getRanking(50);
    $oignonsNext  = 0;
    $nextUser     = null;
    $myData       = ['oignons' => $oignons];

    foreach ($ranking as $i => $p) {
        if ($p['id'] == $_SESSION['user']['id'] && $i > 0) {
            $nextUser    = $ranking[$i - 1];
            $oignonsNext = (int)$nextUser['oignons'];
            break;
        }
    }

    $oignonsManquants = max(0, $oignonsNext - $oignons);
    $pctProg = $oignonsNext > 0
        ? min(100, round($oignons / $oignonsNext * 100))
        : 100;

    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/profileView.php';
    require RACINE . '/app/views/layouts/footer.php';
}

function showEditProfile()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $user = findById($_SESSION['user']['id']);

    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/editProfileView.php';
    require RACINE . '/app/views/layouts/footer.php';
}

function submitUpdatePseudo()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $pseudo = htmlspecialchars(trim($_POST['pseudo'] ?? ''));

    if (strlen($pseudo) < 3) {
        $_SESSION['error'] = "Le pseudo doit faire au moins 3 caractères.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }

    updatePseudo($_SESSION['user']['id'], $pseudo);
    $_SESSION['user']['pseudo'] = $pseudo;
    $_SESSION['success'] = "Pseudo mis à jour !";
    header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
}

function submitUpdateEmail()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $email           = trim($_POST['email'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $user            = findById($_SESSION['user']['id']);

    if (!password_verify($currentPassword, $user['password'])) {
        $_SESSION['error'] = "Mot de passe incorrect.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresse email invalide.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }
    if (findByEmail($email) && $email !== $user['email']) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }

    updateEmail($_SESSION['user']['id'], $email);
    $_SESSION['success'] = "Email mis à jour !";
    header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
}

function submitUpdatePassword()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $user    = findById($_SESSION['user']['id']);

    if (!password_verify($current, $user['password'])) {
        $_SESSION['error'] = "Mot de passe actuel incorrect.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }
    if ($new !== $confirm) {
        $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }
    if (strlen($new) < 8) {
        $_SESSION['error'] = "Le mot de passe doit faire au moins 8 caractères.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }
    if (!preg_match('/[A-Z]/', $new)) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins une majuscule.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }
    if (!preg_match('/[0-9]/', $new)) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins un chiffre.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }
    if (!preg_match('/[\W_]/', $new)) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins un caractère spécial.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }

    updatePassword($_SESSION['user']['id'], $new);
    $_SESSION['success'] = "Mot de passe mis à jour !";
    header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
}

function submitDeleteAccount()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $current = $_POST['current_password'] ?? '';
    $user    = findById($_SESSION['user']['id']);

    if (!password_verify($current, $user['password'])) {
        $_SESSION['error'] = "Mot de passe incorrect. Compte non supprimé.";
        header('Location: ' . BASE_URL . '/index.php?action=editProfile'); exit();
    }

    deleteUser($_SESSION['user']['id']);
    session_destroy();
    session_start();
    $_SESSION['success'] = "Votre compte a été supprimé.";
    header('Location: ' . BASE_URL . '/index.php'); exit();
}
