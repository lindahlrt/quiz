<?php

function showTraining()
{
    $mode       = $_GET['mode'] ?? 'menu';
    $difficulty = $_GET['difficulty'] ?? 'easy';

    // ── Menu ─────────────────────────────────────────
    if ($mode === 'menu') {
        $totalSessions = 0;
        $totalQ        = 0;
        $winRate       = 0;

        if (isset($_SESSION['user'])) {
            $totalSessions = getTrainingSessions($_SESSION['user']['id']);
            $history       = getGameHistory($_SESSION['user']['id'], 20);
            $totalCorrect  = 0;
            foreach ($history as $g) {
                $totalCorrect += $g['correct_answers'];
                $totalQ       += $g['total_questions'];
            }
            $winRate = $totalQ > 0 ? round($totalCorrect / $totalQ * 100) : 0;
        }

        require RACINE . '/app/views/layouts/header.php';
        require RACINE . '/app/views/trainingMenuView.php';
        require RACINE . '/app/views/layouts/footer.php';
        return;
    }

    // ── Jeu ──────────────────────────────────────────
    if (!isset($_SESSION['training']) || isset($_GET['restart'])) {
        $_SESSION['training'] = [
            'questions'    => [], 'current' => 0,
            'correct'      => 0,  'total_done' => 0,
            'difficulty'   => $difficulty, 'batch' => 0,
            'joker_fou'    => true, 'joker_pigeon' => true,
        ];
    }

    $t = &$_SESSION['training'];

    // Charge une nouvelle page de questions depuis l'API si nécessaire
    if (empty($t['questions']) || $t['current'] >= count($t['questions'])) {
        $apiUrl = "https://opentdb.com/api.php?amount=10&difficulty={$t['difficulty']}&type=multiple&encode=url3986";
        $json   = @file_get_contents($apiUrl);
        $data   = $json ? json_decode($json, true) : null;

        if ($data && $data['response_code'] === 0 && !empty($data['results'])) {
            $questions = [];
            foreach ($data['results'] as $q) {
                $question   = urldecode($q['question']);
                $correct    = urldecode($q['correct_answer']);
                $incorrects = array_map('urldecode', $q['incorrect_answers']);
                $answers    = array_merge([$correct], $incorrects);
                shuffle($answers);
                $questions[] = [
                    'question'       => $question,
                    'answer1'        => $answers[0],
                    'answer2'        => $answers[1],
                    'answer3'        => $answers[2],
                    'answer4'        => $answers[3],
                    'correct_answer' => array_search($correct, $answers) + 1,
                    'category'       => urldecode($q['category']),
                ];
            }
            $t['questions'] = $questions;
            $t['current']   = 0;
            $t['batch']++;
        } else {
            $apiError = true;
        }
    }

    // Traite une réponse soumise
    $feedback  = null;
    $feedbackQ = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['t_answer'], $_POST['t_qindex'])) {
        $qindex     = (int)$_POST['t_qindex'];
        $userAnswer = (int)$_POST['t_answer'];
        $q          = $t['questions'][$qindex] ?? null;

        if ($q) {
            $isCorrect = ($userAnswer === (int)$q['correct_answer']);
            $t['total_done']++;
            if ($isCorrect) $t['correct']++;
            $feedback  = $isCorrect ? 'correct' : 'wrong';
            $feedbackQ = $q;
            $t['current'] = $qindex + 1;
        }
    }

    $qindex        = $t['current'];
    $question      = $t['questions'][$qindex] ?? null;
    $qNum          = $t['total_done'] + 1;
    $winRate       = $t['total_done'] > 0 ? round($t['correct'] / $t['total_done'] * 100) : 0;
    $fouUsed       = !$t['joker_fou'];
    $pigeonUsed    = !$t['joker_pigeon'];
    $correctAnswer = $question ? (int)$question['correct_answer'] : 0;
    $wrongAnswers  = $question ? array_diff([1,2,3,4], [$correctAnswer]) : [];
    shuffle($wrongAnswers);
    $toEliminate   = array_slice($wrongAnswers, 0, 2);

    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/trainingView.php';
    require RACINE . '/app/views/layouts/footer.php';
}

function submitTrainingAnswer()
{
    // Le traitement de la réponse se fait dans showTraining() via POST
    // Cette action redirige simplement vers le mode play
    $difficulty = $_GET['difficulty'] ?? 'easy';
    header('Location: ' . BASE_URL . '/index.php?action=training&mode=play&difficulty=' . $difficulty);
    exit();
}

function useTrainingJoker()
{
    if (!isset($_SESSION['user'], $_SESSION['training'])) {
        http_response_code(403); exit();
    }
    $joker = $_GET['joker'] ?? '';
    if ($joker === 'fou')    $_SESSION['training']['joker_fou']    = false;
    if ($joker === 'pigeon') $_SESSION['training']['joker_pigeon'] = false;
    http_response_code(200); exit();
}

function saveTrainingStat()
{
    if (!isset($_SESSION['training'])) {
        http_response_code(403); exit();
    }
    $correct = (int)($_GET['correct'] ?? 0);
    $_SESSION['training']['total_done']++;
    if ($correct) $_SESSION['training']['correct']++;
    http_response_code(200); exit();
}
