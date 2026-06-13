<?php
// Passer la difficulté au JS
$diffJs = htmlspecialchars($difficulty ?? 'easy');
?>

<!-- Overlay joker -->
<div id="joker-overlay" class="joker-overlay" style="display:none" role="dialog" aria-modal="true">
  <div class="joker-overlay-box">
    <div class="joker-overlay-icon" id="joker-overlay-icon" aria-hidden="true"></div>
    <p class="joker-overlay-msg" id="joker-overlay-msg"></p>
    <button onclick="closeJokerOverlay()" class="btn-primary btn-joker-overlay">Continuer</button>
  </div>
</div>

<div class="game-layout">

  <!-- Sidebar gauche -->
  <aside class="game-scale-desktop" aria-label="Statistiques d'entraînement">
    <div class="game-scale-title">ENTRAÎNEMENT</div>
    <div class="training-side-info">
      <div class="training-side-stat"><span id="stat-correct">0</span><small>Bonnes réponses</small></div>
      <div class="training-side-stat"><span id="stat-total">0</span><small>Questions faites</small></div>
      <div class="training-side-stat"><span id="stat-winrate">0%</span><small>Réussite</small></div>
    </div>
    <div class="training-side-badge"><i class="fa-solid fa-carrot" aria-hidden="true"></i> Hors classement</div>
    <a href="index.php?action=training" class="training-side-quit">
      <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Quitter
    </a>
  </aside>

  <!-- Centre -->
  <section class="game-center" id="training-main" aria-label="Question d'entraînement">

    <!-- Barre de progression -->
    <div class="game-progress-row" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="10">
      <span>Session</span>
      <div class="game-progress-bar">
        <div class="game-progress-fill" id="training-progress-fill" style="width:0%"></div>
      </div>
      <span class="game-progress-num" id="training-q-num-badge">Q1</span>
    </div>

    <!-- Feedback -->
    <div id="training-feedback" class="feedback-box" style="display:none" role="status"></div>

    <!-- Catégorie -->
    <div class="training-category" id="training-category">Chargement...</div>

    <!-- Question -->
    <div class="game-question-card">
      <div class="game-q-num training-q-num" id="training-q-num">ENTRAÎNEMENT — QUESTION 1</div>
      <p class="game-q-text" id="training-q-text">Chargement de la question...</p>
    </div>

    <!-- Réponses -->
    <div class="game-answers" role="group" aria-label="Choisissez une réponse">
      <?php foreach ([1,2,3,4] as $i): ?>
        <label class="answer" id="answer-t-<?= $i ?>" onclick="selectAnswerT(this, <?= $i ?>)">
          <input type="radio" name="t_answer" value="<?= $i ?>" class="sr-only"
                 aria-label="Réponse <?= chr(64+$i) ?>">
          <span class="answer-letter" aria-hidden="true"><?= chr(64+$i) ?></span>
          <span class="answer-text">...</span>
        </label>
      <?php endforeach; ?>
    </div>

    <!-- Jokers + Confirmer -->
    <div class="game-action-row">
      <button type="button" id="btn-t-fou" class="btn-joker-pill"
              aria-label="Joker Fou du roi">
        <i class="fa-solid fa-chess-knight" aria-hidden="true"></i> Fou du roi
      </button>
      <button type="button" id="btn-t-pigeon" class="btn-joker-pill"
              aria-label="Joker Pigeon au fermier">
        <i class="fa-solid fa-dove" aria-hidden="true"></i> Pigeon au fermier
      </button>
      <button type="button" id="training-confirm" class="btn-confirm training-btn-confirm" data-state="confirm" disabled>
        Confirmer
      </button>
    </div>

    <a href="index.php?action=training&mode=play&difficulty=<?= $diffJs ?>&restart=1"
       class="btn-outline training-btn-new">
      <i class="fa-solid fa-rotate" aria-hidden="true"></i> Nouvelle session
    </a>
    <a href="index.php?action=training" class="training-quit-link">
      <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Quitter l'entraînement
    </a>

  </section>

  <!-- Sidebar droite -->
  <aside class="game-sidebar" aria-label="Informations de session">
    <div class="sidebar-section-title">JOKERS</div>
    <div class="sidebar-joker" id="sidebar-joker-fou">
      <div class="sidebar-joker-icon" aria-hidden="true"><i class="fa-solid fa-chess-knight"></i></div>
      <div class="sidebar-joker-info"><strong>Fou du roi</strong><p>Élimine 2 mauvaises réponses</p></div>
      <span class="badge-dispo">Dispo</span>
    </div>
    <div class="sidebar-joker" id="sidebar-joker-pigeon">
      <div class="sidebar-joker-icon" aria-hidden="true"><i class="fa-solid fa-dove"></i></div>
      <div class="sidebar-joker-info"><strong>Pigeon au fermier</strong><p>Demande l'avis d'un expert</p></div>
      <span class="badge-dispo">Dispo</span>
    </div>
    <div class="sidebar-section-title game-sidebar-mt">SESSION EN COURS</div>
    <div class="sidebar-stats">
      <div class="sidebar-stat"><span class="stat-val" id="stat-correct-sidebar">0</span><span class="stat-lbl">Bonnes</span></div>
      <div class="sidebar-stat"><span class="stat-val" id="stat-wrong-sidebar">0</span><span class="stat-lbl">Mauvaises</span></div>
      <div class="sidebar-stat"><span class="stat-val" id="stat-winrate-sidebar">0%</span><span class="stat-lbl">Réussite</span></div>
      <div class="sidebar-stat"><span class="stat-val" id="stat-total-sidebar">0</span><span class="stat-lbl">Total</span></div>
    </div>
  </aside>

</div>

<script>
  window.TRAINING_DIFFICULTY = '<?= $diffJs ?>';
</script>
