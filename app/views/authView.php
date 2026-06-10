<?php

?>

<main id="main-content">

  <?php if ($mode === 'login' || $mode === 'register'): ?>

    <div class="auth-wrap">

      <!-- Panneau gauche -->
      <div class="auth-left-panel" aria-hidden="true">
        <img src="<?= BASE_URL ?>/public/images/onion_transparent.webp" alt="" aria-hidden="true" class="auth-left-deco" width="120" height="110">
        <h2><?= $mode === 'login' ? "Bon retour<br>à la cour !" : "Rejoignez<br>la cour !" ?></h2>
        <p>
          <?= $mode === 'login'
            ? "Vos oignons vous attendent. Reconnectez-vous pour reprendre là où vous en étiez."
            : "Créez votre compte et commencez à accumuler des oignons." ?>
        </p>
        <ul class="auth-features" role="list">
          <li class="auth-feature">
            <span class="auth-feature-icon" aria-hidden="true">🧅</span>
            Retrouvez votre progression
          </li>
          <li class="auth-feature">
            <span class="auth-feature-icon" aria-hidden="true"><i class="fa-solid fa-trophy" aria-hidden="true"></i></span>
            Votre rang vous attend
          </li>
          <li class="auth-feature">
            <span class="auth-feature-icon" aria-hidden="true"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i></span>
            Vos jokers sont prêts
          </li>
        </ul>
      </div>

      <!-- Panneau droit -->
      <div class="auth-right-panel">
        <div class="auth-form-wrap">

          <!-- Retour -->
          <div class="auth-back-bar">
            <a href="index.php?page=auth" aria-label="Retour">&#8592;</a>
            <span><?= $mode === 'login' ? 'Se connecter' : 'Créer un compte' ?></span>
          </div>

          <div class="auth-card">

            <!-- Tabs -->
            <nav class="tabs" aria-label="Choix connexion / inscription">
              <a href="index.php?action=loginPage"
                class="<?= $mode === 'login' ? 'active' : '' ?>"
                <?= $mode === 'login' ? 'aria-current="page"' : '' ?>>Connexion</a>
              <a href="index.php?action=registerPage"
                class="<?= $mode === 'register' ? 'active' : '' ?>"
                <?= $mode === 'register' ? 'aria-current="page"' : '' ?>>Inscription</a>
            </nav>

            <?php if ($mode === 'login'): ?>

              <h2>Se connecter</h2>
              <p class="auth-sub">Heureux de vous revoir à la cour.</p>

              <form method="POST" action="index.php?action=login" novalidate>
                <div class="form-group">
                  <label for="email">Adresse email</label>
                  <input type="email" id="email" name="email"
                    placeholder="jean@exemple.fr"
                    autocomplete="email" required>
                </div>
                <div class="form-group">
                  <label for="password">Mot de passe</label>
                  <input type="password" id="password" name="password"
                    placeholder="••••••••"
                    autocomplete="current-password" required>
                  <div class="forgot">
                    <a href="#">Mot de passe oublié ?</a>
                  </div>
                </div>
                <button type="submit" class="btn-primary">Se connecter</button>
              </form>
              <p class="auth-link">Pas encore de compte ? <a href="index.php?action=registerPage">S'inscrire</a></p>

            <?php else: ?>

              <h2>Créer un compte</h2>
              <p class="auth-sub">Rejoignez la cour et récoltez des oignons.</p>

              <form method="POST" action="index.php?action=register" novalidate>
                <div class="form-group">
                  <label for="pseudo">Pseudo</label>
                  <input type="text" id="pseudo" name="pseudo"
                    placeholder="RoiDeLOignon"
                    autocomplete="username" required>
                  <small>Visible sur le classement</small>
                </div>
                <div class="form-group">
                  <label for="email">Adresse email</label>
                  <input type="email" id="email" name="email"
                    placeholder="jean@exemple.fr"
                    autocomplete="email" required>
                </div>
                <div class="form-group">
                  <label for="password">Mot de passe</label>
                  <input type="password" id="password" name="password"
                    placeholder="8 caractères minimum"
                    autocomplete="new-password" required minlength="8">
                  <div class="pwd-strength-bar" id="pwd-bar" aria-hidden="true">
                    <div class="pwd-strength-fill" id="pwd-fill"></div>
                  </div>
                  <p class="pwd-strength-label" id="pwd-label" aria-live="polite"></p>
                </div>
                <div class="form-group">
                  <label for="password_confirm">Confirmer le mot de passe</label>
                  <input type="password" id="password_confirm" name="password_confirm"
                    placeholder="••••••••"
                    autocomplete="new-password" required>
                  <p class="pwd-strength-label" id="pwd-confirm-label" aria-live="polite"></p>
                </div>
                <button type="submit" class="btn-primary">Créer mon compte</button>
              </form>
              <p class="auth-link">Déjà un compte ? <a href="index.php?action=loginPage">Se connecter</a></p>

            <?php endif; ?>

          </div>
        </div>
      </div>

    </div>

  <?php else: ?>

    <!-- Splash screen -->
    <div class="auth-wrap">
      <div class="auth-splash">
        <img src="<?= BASE_URL ?>/public/images/onion_transparent.webp" alt="" aria-hidden="true" class="auth-splash-logo" width="120" height="110">
        <h1>Qui veut gagner<br>des <span>oignons</span> ?</h1>
        <p>Répondez à des questions de plus en plus difficiles pour accumuler des oignons… et la gloire éternelle (ou presque).</p>
        <div class="auth-splash-actions">
          <a href="index.php?action=loginPage" class="btn-primary">Se connecter</a>
          <a href="index.php?action=registerPage" class="btn-auth-secondary">Créer un compte</a>
        </div>
      </div>
    </div>

  <?php endif; ?>

</main>