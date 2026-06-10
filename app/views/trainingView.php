

  <!-- Overlay joker -->
  <div id="joker-overlay" class="joker-overlay" style="display:none" role="dialog" aria-modal="true">
    <div class="joker-overlay-box">
      <div class="joker-overlay-icon" id="joker-overlay-icon" aria-hidden="true"></div>
      <p class="joker-overlay-msg" id="joker-overlay-msg"></p>
      <button onclick="closeJokerOverlay()" class="btn-primary btn-joker-overlay">Continuer</button>
    </div>
  </div>

  <!-- Layout desktop -->
  <div class="game-layout">

    <!-- Sidebar gauche -->
    <aside class="game-scale-desktop" aria-label="Statistiques d'entraînement">
      <div class="game-scale-title">ENTRAÎNEMENT</div>
      <div class="training-side-info">
        <div class="training-side-stat"><span><?= $t['correct'] ?></span><small>Bonnes réponses</small></div>
        <div class="training-side-stat"><span><?= $t['total_done'] ?></span><small>Questions faites</small></div>
        <div class="training-side-stat"><span><?= $winRate ?>%</span><small>Réussite</small></div>
      </div>
      <div class="training-side-badge"><i class="fa-solid fa-carrot" aria-hidden="true"></i> Hors classement</div>
      <a href="index.php?action=training" class="training-side-quit"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Quitter</a>
    </aside>

    <!-- Centre -->
    <section class="game-center" aria-label="Question d'entraînement">

      <?php if (!empty($apiError)): ?>
        <div class="card empty-state training-error-card">
          <div class="training-error-icon" aria-hidden="true"><i class="fa-solid fa-face-dizzy" aria-hidden="true"></i></div>
          <h2>API indisponible</h2>
          <p class="training-error-text">Impossible de charger les questions. Vérifiez votre connexion.</p>
          <a href="index.php?action=training&mode=play&difficulty=easy"
             class="btn-primary btn-inline training-retry-btn">Réessayer</a>
        </div>

      <?php elseif ($question): ?>

        <!-- Barre de progression -->
        <div class="game-progress-row" role="progressbar" aria-valuenow="<?= $qNum ?>" aria-valuemin="1" aria-valuemax="10">
          <span>Session</span>
          <div class="game-progress-bar">
            <div class="game-progress-fill" class="training-progress-fill-green" style="width:<?= min(100, ($t['total_done'] % 10) * 10) ?>%"></div>
          </div>
          <span class="game-progress-num">Q<?= $qNum ?></span>
        </div>

        <!-- Topbar badges -->
        <div class="game-topbar" aria-label="État de la partie">
          <span class="game-badge">Q<?= $qNum ?></span>
          <span class="game-badge"><i class="fa-solid fa-carrot" aria-hidden="true"></i> Facile</span>
          <span class="game-badge"><i class="fa-solid fa-check" aria-hidden="true"></i> <?= $t['correct'] ?>/<?= $t['total_done'] ?></span>
          <span class="game-badge <?= $fouUsed ? 'badge-used' : '' ?>"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i> <?= $fouUsed ? 'Utilisé' : 'Dispo' ?></span>
        </div>

        <!-- Feedback -->
        <?php if ($feedback): ?>
          <div class="feedback-box <?= $feedback === 'correct' ? 'feedback-correct' : 'feedback-wrong' ?>" role="status">
            <?php if ($feedback === 'correct'): ?>
              <i class="fa-solid fa-check" aria-hidden="true"></i> Bonne réponse !
            <?php else: ?>
              <i class="fa-solid fa-xmark" aria-hidden="true"></i> Mauvaise réponse. La bonne réponse était :
              <strong><?= htmlspecialchars($feedbackQ['answer'.$feedbackQ['correct_answer']]) ?></strong>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- Catégorie -->
        <div class="training-category">
          📚 <?= htmlspecialchars($question['category']) ?>
        </div>

        <!-- Question -->
        <div class="game-question-card">
          <div class="game-q-num training-q-num">ENTRAÎNEMENT — QUESTION <?= $qNum ?></div>
          <p class="game-q-text" id="question-text"><?= htmlspecialchars($question['question']) ?></p>
        </div>

        <!-- Réponses + Jokers + Submit -->
        <form method="POST" action="index.php?action=training&mode=play&difficulty=<?= htmlspecialchars($difficulty) ?>"
              aria-labelledby="question-text">
          <input type="hidden" name="t_qindex" value="<?= $qindex ?>">
          <div class="game-answers" role="group" aria-label="Choisissez une réponse">
            <?php foreach ([1,2,3,4] as $i): ?>
              <label class="answer" id="answer-t-<?= $i ?>" onclick="selectAnswerT(this, <?= $i ?>)">
                <input type="radio" name="t_answer" value="<?= $i ?>" class="sr-only" required
                       aria-label="Réponse <?= chr(64+$i) ?> : <?= htmlspecialchars($question['answer'.$i]) ?>">
                <span class="answer-letter" aria-hidden="true"><?= chr(64+$i) ?></span>
                <?= htmlspecialchars($question['answer'.$i]) ?>
              </label>
            <?php endforeach; ?>
          </div>

          <!-- Ligne jokers + confirmer -->
          <div class="game-action-row">
            <button type="button" id="btn-t-fou"
                    class="btn-joker-pill <?= $fouUsed ? 'btn-joker-used' : '' ?>"
                    onclick="useFouT()"
                    <?= $fouUsed ? 'disabled aria-disabled="true"' : '' ?>
                    aria-label="Joker Fou du roi<?= $fouUsed ? ' (utilisé)' : '' ?>">
              <span aria-hidden="true"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i></span> Fou du roi
            </button>
            <button type="button" id="btn-t-pigeon"
                    class="btn-joker-pill <?= $pigeonUsed ? 'btn-joker-used' : '' ?>"
                    onclick="usePigeonT()"
                    <?= $pigeonUsed ? 'disabled aria-disabled="true"' : '' ?>
                    aria-label="Joker Pigeon<?= $pigeonUsed ? ' (utilisé)' : '' ?>">
              <span aria-hidden="true"><i class="fa-solid fa-dove" aria-hidden="true"></i></span> Pigeon au fermier
            </button>
            <button type="submit" class="btn-confirm training-btn-confirm">Confirmer</button>
          </div>
          <a href="index.php?action=training&mode=play&difficulty=<?= htmlspecialchars($difficulty) ?>&restart=1"
             class="btn-outline training-btn-new"><i class="fa-solid fa-rotate" aria-hidden="true"></i> Nouvelle session</a>
        </form>

        <a href="index.php?action=training" class="training-quit-link"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Quitter l'entraînement</a>

      <?php endif; ?>
    </section>

    <!-- Sidebar droite (desktop) -->
    <aside class="game-sidebar" aria-label="Informations de session">
      <div class="sidebar-section-title">JOKERS</div>
      <div class="sidebar-joker <?= $fouUsed ? 'sidebar-joker-used' : '' ?>">
        <div class="sidebar-joker-icon" aria-hidden="true"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i></div>
        <div class="sidebar-joker-info"><strong>Fou du roi</strong><p>Élimine 2 mauvaises réponses</p></div>
        <?= $fouUsed ? '<span class="badge-used">Utilisé</span>' : '<span class="badge-dispo">Dispo</span>' ?>
      </div>
      <div class="sidebar-joker <?= $pigeonUsed ? 'sidebar-joker-used' : '' ?>">
        <div class="sidebar-joker-icon" aria-hidden="true"><i class="fa-solid fa-dove" aria-hidden="true"></i></div>
        <div class="sidebar-joker-info"><strong>Pigeon au fermier</strong><p>Demande l'avis d'un expert</p></div>
        <?= $pigeonUsed ? '<span class="badge-used">Utilisé</span>' : '<span class="badge-dispo">Dispo</span>' ?>
      </div>
      <div class="sidebar-section-title game-sidebar-mt">SESSION EN COURS</div>
      <div class="sidebar-stats">
        <div class="sidebar-stat"><span class="stat-val"><?= $t['correct'] ?></span><span class="stat-lbl">Bonnes</span></div>
        <div class="sidebar-stat"><span class="stat-val"><?= max(0, $t['total_done'] - $t['correct']) ?></span><span class="stat-lbl">Mauvaises</span></div>
        <div class="sidebar-stat"><span class="stat-val"><?= $winRate ?>%</span><span class="stat-lbl">Réussite</span></div>
        <div class="sidebar-stat"><span class="stat-val"><?= $t['total_done'] ?></span><span class="stat-lbl">Total</span></div>
      </div>
    </aside>

  </div>

</main>