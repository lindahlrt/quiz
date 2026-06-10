<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Qui veut gagner des oignons ? Un quiz inspiré du célèbre jeu télévisé. Répondez aux questions et accumulez des oignons !">
  <title>Quiz Oignons</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" type="image/webp" href="<?= BASE_URL ?>/public/images/onion.webp">
</head>
<body>

<a class="skip-link" href="#main-content">Aller au contenu principal</a>

<header>
  <nav class="site-nav" aria-label="Navigation principale">
    <div class="nav-inner">

      <!-- Logo -->
      <a href="index.php?action=default" class="nav-logo" aria-label="Accueil — Qui veut gagner des oignons ?">
        <img src="<?= BASE_URL ?>/public/images/onion.webp" alt="Logo Qui veut gagner des oignons" class="nav-logo-icon" width="32" height="32">
        <span><strong>Qui veut gagner des</strong> <em>oignons</em> ?</span>
      </a>

      <!-- Liens desktop (cachés sur mobile) -->
      <?php if (isset($_SESSION['user'])): ?>
        <nav class="nav-links" aria-label="Menu principal">
          <a href="index.php?action=default"
             <?= ($currentAction??'default')==='default' ? 'aria-current="page"' : '' ?>>Accueil</a>
          <a href="index.php?action=game"
             <?= ($currentAction??'')==='game' ? 'aria-current="page"' : '' ?>>Jeu</a>
          <a href="index.php?action=training"
             <?= ($currentAction??'')==='training' ? 'aria-current="page"' : '' ?>>Entraînement</a>
          <a href="index.php?action=ranking"
             <?= ($currentAction??'')==='ranking' ? 'aria-current="page"' : '' ?>>Scores</a>
          <a href="index.php?action=showProfile"
             <?= ($currentAction??'')==='showProfile' ? 'aria-current="page"' : '' ?>>Profil</a>
        </nav>
        <div class="nav-desktop-right">
          <?php
            $pseudo = $_SESSION['user']['pseudo'] ?? 'Joueur';
            $parts  = explode(' ', $pseudo);
            $initials = strtoupper(substr($parts[0],0,1) . substr($parts[1]??$parts[0],1,1));
          ?>
          <div class="nav-oignons-badge" aria-label="<?= (int)($_SESSION['user']['oignons']??0) ?> oignons">
            <?= (int)($_SESSION['user']['oignons']??0) ?> 🧅
          </div>
          <a href="index.php?action=showProfile" class="nav-avatar"
             aria-label="Profil de <?= htmlspecialchars($pseudo) ?>"><?= $initials ?></a>
          <a href="index.php?action=logout" class="nav-logout-link">Déconnexion</a>
        </div>
      <?php else: ?>
        <nav class="nav-links" aria-label="Menu principal">
          <a href="index.php?action=default">Accueil</a>
          <a href="index.php?action=training">Entraînement</a>
          <a href="index.php?action=loginPage">Connexion</a>
          <a href="index.php?action=registerPage">Inscription</a>
        </nav>
      <?php endif; ?>

      <!-- Burger mobile (badge oignons visible uniquement sur mobile) -->
      <div class="nav-mobile-right">
        <?php if (isset($_SESSION['user'])): ?>
          <div class="nav-oignons-badge nav-oignons-mobile" aria-label="<?= (int)($_SESSION['user']['oignons']??0) ?> oignons">
            <?= (int)($_SESSION['user']['oignons']??0) ?> 🧅
          </div>
        <?php endif; ?>
        <button class="nav-burger"
                onclick="toggleMobileMenu()"
                aria-controls="nav-dropdown"
                aria-expanded="false"
                aria-label="Ouvrir le menu">&#9776;</button>
      </div>

    </div><!-- /.nav-inner -->

    <!-- Menu déroulant mobile -->
    <div class="nav-dropdown" id="nav-dropdown" role="navigation" aria-label="Menu mobile">
      <?php if (isset($_SESSION['user'])): ?>
        <a href="index.php?action=default"
           onclick="toggleMobileMenu()"
           <?= ($currentAction??'default')==='default' ? 'aria-current="page"' : '' ?>>🏠 Accueil</a>
        <a href="index.php?action=game"
           onclick="toggleMobileMenu()"
           <?= ($currentAction??'')==='game' ? 'aria-current="page"' : '' ?>>🎮 Jeu</a>
        <a href="index.php?action=training"
           onclick="toggleMobileMenu()"
           <?= ($currentAction??'')==='training' ? 'aria-current="page"' : '' ?>><i class="fa-solid fa-carrot" aria-hidden="true"></i> Entraînement</a>
        <a href="index.php?action=ranking"
           onclick="toggleMobileMenu()"
           <?= ($currentAction??'')==='ranking' ? 'aria-current="page"' : '' ?>><i class="fa-solid fa-trophy" aria-hidden="true"></i> Scores</a>
        <a href="index.php?action=showProfile"
           onclick="toggleMobileMenu()"
           <?= ($currentAction??'')==='showProfile' ? 'aria-current="page"' : '' ?>>👤 Profil</a>
        <a href="index.php?action=logout" class="nav-logout">Déconnexion</a>
      <?php else: ?>
        <a href="index.php?action=training" onclick="toggleMobileMenu()"><i class="fa-solid fa-carrot" aria-hidden="true"></i> Entraînement</a>
        <a href="index.php?action=loginPage" onclick="toggleMobileMenu()">Se connecter</a>
        <a href="index.php?action=registerPage" onclick="toggleMobileMenu()">Créer un compte</a>
      <?php endif; ?>
    </div>

  </nav>
</header>

<?php if (isset($_SESSION['success'])): ?>
  <div class="flash success" role="alert"><?= htmlspecialchars($_SESSION['success']) ?></div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
  <div class="flash error" role="alert"><?= htmlspecialchars($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>
