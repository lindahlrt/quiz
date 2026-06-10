<?php

function showGame()
{
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Connectez-vous pour jouer.";
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    // Réinitialise la partie si demandé
    if (isset($_GET['restart']) || !isset($_SESSION['game'])) {
        $_SESSION['game'] = [
            'correct'        => 0,
            'total'          => 0,
            'questions_done' => [],
            'current'        => 1,
            'joker_fou'      => true,
            'joker_pigeon'   => true,
            'game_over'      => false,
        ];
    }

    $game           = &$_SESSION['game'];
    $totalAvailable = countQuestions();
    $totalQuestions = 10;
    $gameOver       = $game['game_over'] ?? false;
    $questionNum    = $game['current'];
    $question       = null;

    // Charger la question 
    if (!$gameOver && $totalAvailable > 0 && $questionNum <= $totalQuestions) {
        $allQuestions = getRandomQuestions(50);
        foreach ($allQuestions as $q) {
            if (!in_array($q['id'], $game['questions_done'])) {
                $question = $q;
                break;
            }
        }
    }

    // Infos pour le feedback sur la dernière réponse
    $lastResult = $_GET['last'] ?? null;
    $lastQid    = (int)($_GET['qid'] ?? 0);
    $lastQ      = $lastQid ? getQuestionById($lastQid) : null;

    // Données pour les jokers
    $correctAnswer = $question ? (int)$question['correct_answer'] : 0;
    $wrongAnswers  = $question ? array_diff([1,2,3,4], [$correctAnswer]) : [];
    shuffle($wrongAnswers);
    $toEliminate = array_slice($wrongAnswers, 0, 2);
    $fouUsed     = !$game['joker_fou'];
    $pigeonUsed  = !$game['joker_pigeon'];

    $paliers = [
        10 => ['pts' => '1 000 oignons', 'q' => 'Q10'],
         9 => ['pts' => '500 oignons',   'q' => 'Q9'],
         8 => ['pts' => '200 oignons',   'q' => 'Q8'],
         7 => ['pts' => '100 oignons',   'q' => 'Q7'],
         6 => ['pts' => '50 oignons',    'q' => 'Q6'],
         5 => ['pts' => '25 oignons',    'q' => 'Q5'],
         4 => ['pts' => '15 oignons',    'q' => 'Q4'],
         3 => ['pts' => '10 oignons',    'q' => 'Q3'],
         2 => ['pts' => '5 oignons',     'q' => 'Q2'],
         1 => ['pts' => '1 oignon',      'q' => 'Q1'],
    ];

    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/gameView.php';
    require RACINE . '/app/views/layouts/footer.php';
}

function submitAnswer()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $questionId = (int)($_POST['question_id'] ?? 0);
    $userAnswer = (int)($_POST['answer'] ?? 0);
    $question   = getQuestionById($questionId);

    if (!$question) {
        header('Location: ' . BASE_URL . '/index.php?action=game'); exit();
    }

    if (!isset($_SESSION['game'])) {
        $_SESSION['game'] = ['correct'=>0,'total'=>0,'questions_done'=>[],'answers'=>[],'current'=>1,'joker_fou'=>true,'joker_pigeon'=>true,'game_over'=>false];
    }

    $_SESSION['game']['total']++;
    $_SESSION['game']['questions_done'][] = $questionId;
    $correct = ($userAnswer === (int)$question['correct_answer']);

    // Stocker la réponse pour l'enregistrer en base à la fin
    $_SESSION['game']['answers'][] = [
        'question_id' => $questionId,
        'user_answer' => $userAnswer,
        'is_correct'  => $correct,
    ];

    if ($correct) {
        $_SESSION['game']['correct']++;
        $_SESSION['game']['current']++;
        header('Location: ' . BASE_URL . '/index.php?action=game&last=correct&qid=' . $questionId);
    } else {
        $_SESSION['game']['game_over'] = true;
        header('Location: ' . BASE_URL . '/index.php?action=game&last=wrong&qid=' . $questionId);
    }
    exit();
}

function endGame()
{
    if (!isset($_SESSION['user'], $_SESSION['game'])) {
        header('Location: ' . BASE_URL . '/index.php'); exit();
    }

    $userId  = $_SESSION['user']['id'];
    $correct = $_SESSION['game']['correct'] ?? 0;
    $total   = $_SESSION['game']['total']   ?? 0;
    $answers = $_SESSION['game']['answers'] ?? [];
    $oignons = calcOignons($correct);

    $gameId = saveGame($userId, $correct, $total, $oignons);

    // Enregistrer le détail de chaque réponse
    foreach ($answers as $a) {
        saveGameQuestion($gameId, $a['question_id'], $a['user_answer'], $a['is_correct']);
    }

    addOignons($userId, $oignons);
    $_SESSION['user']['oignons'] += $oignons;
    unset($_SESSION['game']);

    $_SESSION['success'] = "Partie terminée ! +{$oignons} oignons 🧅";
    header('Location: ' . BASE_URL . '/index.php?action=showProfile'); exit();
}

function useJoker()
{
    if (!isset($_SESSION['user'], $_SESSION['game'])) {
        http_response_code(403); exit();
    }
    $joker = $_GET['joker'] ?? '';
    if ($joker === 'fou')    $_SESSION['game']['joker_fou']    = false;
    if ($joker === 'pigeon') $_SESSION['game']['joker_pigeon'] = false;
    http_response_code(200); exit();
}

function calcOignons($correct)
{
    $scale = [10=>200, 9=>150, 8=>100, 7=>75, 6=>50, 5=>25, 4=>15, 3=>10, 2=>5, 1=>1];
    return $scale[$correct] ?? 0;
}
