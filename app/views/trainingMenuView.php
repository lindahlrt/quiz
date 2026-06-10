<main id="main-content">



  <!-- ===== MENU ENTRAÎNEMENT ===== -->
  <div class="page-wrap">

    <!-- HERO : texte + stats côte à côte sur desktop -->
    <section class="training-hero" aria-label="Présentation du mode entraînement">
      <img src="<?= BASE_URL ?>/public/images/onion_transparent.webp" alt="" aria-hidden="true" class="training-hero-deco" width="120" height="110">

      <div class="training-hero-left">
        <h1 class="training-hero-title">Mode entraînement <i class="fa-solid fa-carrot" aria-hidden="true"></i></h1>
        <p class="training-hero-desc">
          Questions aléatoires sans fin, sans pression. Votre score ne compte pas dans le classement général.
        </p>
        <div class="training-hero-btns">
          <a href="index.php?action=training&mode=play&difficulty=easy" class="btn-hero-play"><i class="fa-solid fa-play" aria-hidden="true"></i> Commencer</a>
          <span class="training-hors-classement">Hors classement</span>
        </div>
      </div>

      <div class="training-hero-stats" aria-label="Vos statistiques d'entraînement">
        <div class="training-stat-box">
          <span class="training-stat-val"><?= $totalQ ?></span>
          <span class="training-stat-lbl">Questions</span>
        </div>
        <div class="training-stat-box">
          <span class="training-stat-val"><?= $winRate ?>%</span>
          <span class="training-stat-lbl">Réussite</span>
        </div>
        <div class="training-stat-box">
          <span class="training-stat-val"><?= $totalSessions ?></span>
          <span class="training-stat-lbl">Sessions</span>
        </div>
        <div class="training-stat-box">
          <span class="training-stat-val"><i class="fa-solid fa-carrot" aria-hidden="true"></i></span>
          <span class="training-stat-lbl">Meilleur niveau</span>
        </div>
      </div>
    </section>

    <!-- DIFFICULTÉ + RÈGLES -->
    <div class="training-bottom-grid">

      <section class="card" aria-labelledby="difficulty-title">
        <div class="card-label" id="difficulty-title">Choisissez votre difficulté</div>
        <div class="difficulty-grid">
          <a href="index.php?action=training&mode=play&difficulty=easy" class="difficulty-card difficulty-active">
            <div class="diff-icon" aria-hidden="true"><i class="fa-solid fa-carrot" aria-hidden="true"></i></div>
            <div><strong>Facile</strong><br><small>Pour commencer</small></div>
          </a>
          <div class="difficulty-card difficulty-locked" aria-disabled="true">
            <div class="diff-icon" aria-hidden="true"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></div>
            <div><strong>Normal</strong><br><small>Le vrai défi</small></div>
            <span class="diff-badge-soon">Bientôt</span>
          </div>
          <div class="difficulty-card difficulty-locked" aria-disabled="true">
            <div class="diff-icon" aria-hidden="true"><i class="fa-solid fa-skull" aria-hidden="true"></i></div>
            <div><strong>Difficile</strong><br><small>Pour les experts</small></div>
            <span class="diff-badge-soon">Bientôt</span>
          </div>
        </div>
      </section>

      <section class="card" aria-labelledby="rules-title">
        <div class="card-label" id="rules-title">Règles</div>
        <ul class="rules-list" role="list">
          <li class="rule-row"><span aria-hidden="true"><i class="fa-solid fa-list" aria-hidden="true"></i> Questions aléatoires et infinies</li>
          <li class="rule-row"><span aria-hidden="true"><i class="fa-solid fa-ban" aria-hidden="true"></i> Score non comptabilisé dans le classement</li>
          <li class="rule-row"><span aria-hidden="true"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i></span> Jokers disponibles mais hors classement</li>
          <li class="rule-row"><span aria-hidden="true"><i class="fa-solid fa-chart-bar" aria-hidden="true"></i> Statistiques visibles dans votre profil</li>
        </ul>
      </section>

    </div>

  </div>

</main>

<?php if ($mode === 'play'): ?>
<script>
/* Données injectées par le serveur — passage PHP <i class="fa-solid fa-arrow-right" aria-hidden="true"></i> JS */
const T_CORRECT     = <?= $correctAnswer ?? 0 ?>;
const T_TO_ELIM     = <?= json_encode($toEliminate ?? []) ?>;
const T_FOU_USED    = <?= ($fouUsed ?? false) ? 'true' : 'false' ?>;
const T_PIGEON_USED = <?= ($pigeonUsed ?? false) ? 'true' : 'false' ?>;
</script>
<?php endif; ?>
