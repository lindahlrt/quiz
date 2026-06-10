<?php

function showHome()
{
    $top3     = getTop3();
    $myStats  = null;
    $lastGame = null;
    $position = null;

    if (isset($_SESSION['user'])) {
        $myStats  = getUserStats($_SESSION['user']['id']);
        $history  = getGameHistory($_SESSION['user']['id'], 1);
        $lastGame = $history[0] ?? null;
        $position = getRankPosition($_SESSION['user']['id']);
    }

    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/homeView.php';
    require RACINE . '/app/views/layouts/footer.php';
}
