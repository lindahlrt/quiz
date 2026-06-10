<?php

function showAuthPage($mode = 'login')
{
    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/authView.php';
    require RACINE . '/app/views/layouts/footer.php';
}

function redirectAuth($action, $error = null)
{
    if ($error) $_SESSION['error'] = $error;
    header('Location: ' . BASE_URL . '/index.php?action=' . $action); exit();
}

// ── Connexion ────────────────────────────────────

function login()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectAuth('loginPage');

    initBruteForce();

    if (isLocked()) {
        $mins = ceil(($_SESSION['login_locked_until'] - time()) / 60);
        redirectAuth('loginPage', "Trop de tentatives. Réessayez dans {$mins} minute(s).");
    }

    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // Vérification champs vides
    if (empty($email) || empty($password)) {
        redirectAuth('loginPage', "Veuillez remplir tous les champs.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectAuth('loginPage', "Adresse email invalide.");
    }

    $user = findByEmail($email);

    if ($user && password_verify($_POST['password'], $user['password'])) {
        resetBruteForce();
        $_SESSION['user']    = buildUserSession($user);
        $_SESSION['success'] = "Bon retour à la cour, {$user['pseudo']} 🧅";
        header('Location: ' . BASE_URL . '/index.php'); exit();
    }

    recordFailedAttempt();
}

function initBruteForce()
{
    $_SESSION['login_attempts']    ??= 0;
    $_SESSION['login_locked_until'] ??= 0;
}

function isLocked()
{
    return time() < $_SESSION['login_locked_until'];
}

function resetBruteForce()
{
    $_SESSION['login_attempts']    = 0;
    $_SESSION['login_locked_until'] = 0;
}

function recordFailedAttempt()
{
    $_SESSION['login_attempts']++;
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['login_locked_until'] = time() + 300;
        $_SESSION['login_attempts']     = 0;
        redirectAuth('loginPage', "Trop de tentatives. Compte bloqué 5 minutes.");
    }
    $left = 5 - $_SESSION['login_attempts'];
    redirectAuth('loginPage', "Email ou mot de passe incorrect. ({$left} tentative(s) restante(s))");
}

function buildUserSession($user)
{
    return [
        'id'       => $user['id'],
        'pseudo'   => $user['pseudo'],
        'oignons'  => $user['oignons'] ?? 0,
        'is_admin' => (bool)$user['is_admin'],
    ];
}

// ── Inscription ──────────────────────────────────

function register()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectAuth('registerPage');

    $pseudo   = htmlspecialchars(trim($_POST['pseudo']           ?? ''));
    $email    = trim($_POST['email']                             ?? '');
    $password = $_POST['password']                               ?? '';
    $confirm  = $_POST['password_confirm']                       ?? '';

    validateRegistration($pseudo, $email, $password, $confirm);

    createUser($pseudo, $email, $password);
    $_SESSION['success'] = "Compte créé ! Bienvenue dans la cour 🧅";
    redirectAuth('loginPage');
}

function validateRegistration($pseudo, $email, $password, $confirm)
{
    // Vérification champs vides
    if (empty($pseudo) || empty($email) || empty($password) || empty($confirm))
        redirectAuth('registerPage', "Veuillez remplir tous les champs.");

    if (strlen($pseudo) < 3 || strlen($pseudo) > 30)
        redirectAuth('registerPage', "Le pseudo doit faire entre 3 et 30 caractères.");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        redirectAuth('registerPage', "Adresse email invalide.");

    if (strlen($password) < 8)
        redirectAuth('registerPage', "Le mot de passe doit faire au moins 8 caractères.");

    if (!preg_match('/[A-Z]/', $password))
        redirectAuth('registerPage', "Le mot de passe doit contenir au moins une majuscule.");

    if (!preg_match('/[0-9]/', $password))
        redirectAuth('registerPage', "Le mot de passe doit contenir au moins un chiffre.");

    if (!preg_match('/[\W_]/', $password))
        redirectAuth('registerPage', "Le mot de passe doit contenir au moins un caractère spécial.");

    if ($password !== $confirm)
        redirectAuth('registerPage', "Les mots de passe ne correspondent pas.");

    if (findByEmail($email))
        redirectAuth('registerPage', "Cet email est déjà utilisé.");
}

// ── Déconnexion ──────────────────────────────────

function logout()
{
    session_destroy();
    session_start();
    $_SESSION['success'] = "À bientôt !";
    header('Location: ' . BASE_URL . '/index.php'); exit();
}
