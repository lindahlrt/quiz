<main id="main-content">

<div class="page-wrap">
  <div class="edit-profile-container">

    <header class="edit-profile-header">
      <a href="index.php?action=showProfile" class="edit-back"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour au profil</a>
      <h1 class="edit-page-title">Modifier le profil</h1>
    </header>

    <div class="edit-profile-grid">

      <aside class="card edit-preview-card" aria-label="Aperçu du profil">
        <div class="profile-avatar-lg" id="preview-avatar"><?= initials($user['pseudo']) ?></div>
        <p class="profile-name" id="preview-name"><?= htmlspecialchars($user['pseudo']) ?></p>
        <p class="profile-since"><?= htmlspecialchars($user['email']) ?></p>
        <p class="edit-preview-hint">Aperçu de votre profil</p>
      </aside>

      <div class="edit-forms">

        <section class="card edit-section" aria-labelledby="pseudo-title">
          <h2 class="card-label" id="pseudo-title">PSEUDO</h2>
          <p class="edit-section-desc">Votre pseudo est visible sur le classement public.</p>
          <form method="POST" action="index.php?action=updatePseudo">
            <div class="auth-field">
              <label for="pseudo-input">Nouveau pseudo</label>
              <input type="text" name="pseudo" value="<?= htmlspecialchars($user['pseudo']) ?>" required minlength="3" maxlength="50" id="pseudo-input">
            </div>
            <button type="submit" class="btn-save">Enregistrer le pseudo</button>
          </form>
        </section>

        <section class="card edit-section" aria-labelledby="email-title">
          <h2 class="card-label" id="email-title">ADRESSE EMAIL</h2>
          <p class="edit-section-desc">Utilisée pour vous connecter. Non visible publiquement.</p>
          <form method="POST" action="index.php?action=updateEmail">
            <div class="auth-field">
              <label for="email-input">Nouvelle adresse email</label>
              <input type="email" id="email-input" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="auth-field">
              <label for="email-current-password">Mot de passe actuel (confirmation)</label>
              <input type="password" id="email-current-password" name="current_password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-save">Enregistrer l'email</button>
          </form>
        </section>

        <section class="card edit-section" aria-labelledby="password-title">
          <h2 class="card-label" id="password-title">MOT DE PASSE</h2>
          <p class="edit-section-desc">Choisissez un mot de passe d'au moins 8 caractères.</p>
          <form method="POST" action="index.php?action=updatePassword">
            <div class="auth-field">
              <label for="current-password">Mot de passe actuel</label>
              <input type="password" id="current-password" name="current_password" placeholder="••••••••" required>
            </div>
            <div class="auth-field">
              <label for="new-pass">Nouveau mot de passe</label>
              <input type="password" name="new_password" placeholder="8 caractères minimum" required minlength="8" id="new-pass">
            </div>
            <div class="auth-field">
              <label for="confirm-pass">Confirmer le nouveau mot de passe</label>
              <input type="password" name="confirm_password" placeholder="••••••••" required minlength="8" id="confirm-pass">
            </div>
            <p class="pass-match-msg" id="pass-msg" aria-live="polite"></p>
            <button type="submit" class="btn-save">Changer le mot de passe</button>
          </form>
        </section>

        <section class="card edit-section edit-danger-zone" aria-labelledby="danger-title">
          <h2 class="card-label edit-danger-title" id="danger-title">ZONE DE DANGER</h2>
          <p class="edit-section-desc">La suppression de votre compte est irréversible. Toutes vos données seront perdues.</p>
          <form method="POST" action="index.php?action=deleteAccount" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
            <div class="auth-field">
              <label for="delete-password">Confirmez votre mot de passe</label>
              <input type="password" id="delete-password" name="current_password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-danger">Supprimer mon compte</button>
          </form>
        </section>

      </div>
    </div>
  </div>
</div>

</main>
