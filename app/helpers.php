<?php
// ================================================
// helpers.php — Fonctions utilitaires partagées
// Chargé une fois dans index.php
// ================================================

// Initiales d'un pseudo 
function initials($pseudo)
{
    $pseudo = trim($pseudo);
    $parts  = explode(' ', $pseudo);
    if (count($parts) >= 2) {
        return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    }
    return strtoupper(substr($pseudo, 0, 2));
}

// Couleur d'avatar selon un index
function avatarColor($index)
{
    $colors = ['#8b1e1e','#185fa5','#3b6d11','#854f0b','#533480','#0f6e56','#b45309'];
    return $colors[$index % count($colors)];
}
