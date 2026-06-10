<?php

function requireAdmin()
{
    if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
        $_SESSION['error'] = "Accès réservé aux administrateurs.";
        header('Location: ' . BASE_URL . '/index.php'); exit();
    }
}

function showAdmin()
{
    requireAdmin();
    $questions = getAllQuestions();
    $totalQ    = count($questions);
    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/adminView.php';
    require RACINE . '/app/views/layouts/footer.php';
}

function getQuestionFields()
{
    $fields = array_map(
        fn($v) => htmlspecialchars(trim($v ?? '')),
        [
            'question' => $_POST['question'] ?? '',
            'answer1'  => $_POST['answer1']  ?? '',
            'answer2'  => $_POST['answer2']  ?? '',
            'answer3'  => $_POST['answer3']  ?? '',
            'answer4'  => $_POST['answer4']  ?? '',
        ]
    );
    $fields['correct'] = (int)($_POST['correct_answer'] ?? 0);
    return $fields;
}

function fieldsAreValid($f)
{
    return $f['question'] && $f['answer1'] && $f['answer2']
        && $f['answer3'] && $f['answer4']
        && in_array($f['correct'], [1, 2, 3, 4]);
}

function redirectAdmin()
{
    header('Location: ' . BASE_URL . '/index.php?action=admin'); exit();
}

function submitAddQuestion()
{
    requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectAdmin();

    $f = getQuestionFields();
    if (fieldsAreValid($f)) {
        addQuestion($f['question'], $f['answer1'], $f['answer2'], $f['answer3'], $f['answer4'], $f['correct']);
        $_SESSION['success'] = "Question ajoutée !";
    } else {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
    }
    redirectAdmin();
}

function submitEditQuestion()
{
    requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectAdmin();

    $id = (int)($_POST['id'] ?? 0);
    $f  = getQuestionFields();
    if ($id && fieldsAreValid($f)) {
        updateQuestion($id, $f['question'], $f['answer1'], $f['answer2'], $f['answer3'], $f['answer4'], $f['correct']);
        $_SESSION['success'] = "Question modifiée !";
    } else {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
    }
    redirectAdmin();
}

function submitDeleteQuestion()
{
    requireAdmin();
    $id = (int)($_GET['id'] ?? 0);
    if ($id) {
        deleteQuestion($id);
        $_SESSION['success'] = "Question supprimée.";
    }
    redirectAdmin();
}

function showAdminUsers()
{
    requireAdmin();
    $users  = getAllUsers();
    $totalU = count($users);
    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/adminUsersView.php';
    require RACINE . '/app/views/layouts/footer.php';
}

function submitDeleteUser()
{
    requireAdmin();
    $id = (int)($_GET['id'] ?? 0);

    // Empêche la suppression de son propre compte
    if ($id && $id !== $_SESSION['user']['id']) {
        deleteUserById($id);
        $_SESSION['success'] = "Utilisateur supprimé.";
    } else {
        $_SESSION['error'] = "Impossible de supprimer ce compte.";
    }
    header('Location: ' . BASE_URL . '/index.php?action=adminUsers'); exit();
}

function submitToggleAdmin()
{
    requireAdmin();
    $id      = (int)($_GET['id'] ?? 0);
    $isAdmin = (int)($_GET['is_admin'] ?? 0);

    // Empêche de se retirer ses propres droits admin
    if ($id && $id !== $_SESSION['user']['id']) {
        toggleAdmin($id, $isAdmin);
        $_SESSION['success'] = $isAdmin ? "Droits admin accordés." : "Droits admin retirés.";
    } else {
        $_SESSION['error'] = "Impossible de modifier vos propres droits.";
    }
    header('Location: ' . BASE_URL . '/index.php?action=adminUsers'); exit();
}
