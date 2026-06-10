<?php

function showRanking()
{
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Connectez-vous pour accéder au classement.";
        header('Location: ' . BASE_URL . '/index.php?action=loginPage'); exit();
    }

    $ranking  = getRanking(50);
    $top3     = array_slice($ranking, 0, 3);
    $myRank   = getRankPosition($_SESSION['user']['id']);

    // Données de l'utilisateur connecté dans le classement
    $myData   = null;
    $nextUser = null;
    $oignonsManquants = 0;
    foreach ($ranking as $i => $player) {
        if ($player['id'] == $_SESSION['user']['id']) {
            $myData = $player;
            if ($i > 0) {
                $nextUser = $ranking[$i - 1];
                $oignonsManquants = max(0, (int)$nextUser['oignons'] - (int)$myData['oignons']);
            }
            break;
        }
    }

    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/rankingView.php';
    require RACINE . '/app/views/layouts/footer.php';
}
