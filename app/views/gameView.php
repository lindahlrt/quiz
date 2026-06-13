<main id="main-content">

<?php if ($totalAvailable === 0): ?>

  <div class="page-wrap">
    <div class="card empty-state game-empty-card">
      <div class="game-empty-icon" aria-hidden="true">🧅</div>
      <h2>Aucune question disponible</h2>
      <p class="game-empty-text">L'administrateur n'a pas encore créé de questions.</p>
    </div>
  </div>

<?php elseif ($gameOver): ?>

  <div class="page-wrap">
    <div class="card game-over-card">
      <div class="game-over-icon" aria-hidden="true"><i class="fa-solid fa-skull"></i></div>
      <h2 class="game-over-title">Partie terminée !</h2>
      <p class="game-over-sub">Mauvaise réponse… vous repartez sans oignons.</p>
      <?php if ($lastQ): ?>
        <div class="game-over-answer">
          La bonne réponse était : <strong><?= htmlspecialchars($lastQ['answer'.$lastQ['correct_answer']]) ?></strong>
        </div>
      <?php endif; ?>
      <div class="game-over-score">
        Vous avez répondu correctement à
        <strong><?= $game['correct'] ?> question<?= $game['correct'] > 1 ? 's' : '' ?></strong>
        sur <?= $game['total'] ?>
      </div>
      <?php
        $palierPts = [10=>200,9=>150,8=>100,7=>75,6=>50,5=>25,4=>15,3=>10,2=>5,1=>1];
        $oignonsGagnes = $palierPts[$game['correct'] - 1] ?? 0;
      ?>
      <?php if ($game['correct'] > 1): ?>
        <p class="game-over-palier">
          Vous repartez avec les oignons du palier précédent : +<?= $oignonsGagnes ?> 🧅
        </p>
      <?php endif; ?>
      <div class="game-over-actions">
        <a href="index.php?action=endGame" class="btn-primary btn-inline">
          Valider et récupérer mes oignons
        </a>
        <a href="index.php?action=game&restart=1" class="btn-outline btn-inline">Rejouer</a>
      </div>
    </div>
  </div>

<?php elseif ($question && $questionNum <= $totalQuestions): ?>

  <!-- Overlay joker -->
  <div id="joker-overlay" class="joker-overlay" style="display:none" role="dialog" aria-modal="true" aria-labelledby="joker-overlay-msg">
    <div class="joker-overlay-box">
      <div class="joker-overlay-icon" id="joker-overlay-icon" aria-hidden="true"></div>
      <p class="joker-overlay-msg" id="joker-overlay-msg"></p>
      <button class="btn-primary btn-joker-overlay" id="btn-close-overlay">Continuer</button>
    </div>
  </div>

  <!-- Layout desktop : grille 3 colonnes -->
  <div class="game-layout">

    <!-- Échelle -->
    <aside class="game-scale-desktop" aria-label="Échelle des oignons">
      <div class="game-scale-title">ÉCHELLE DES OIGNONS</div>
      <?php foreach ($paliers as $n => $p): ?>
        <div class="scale-row-d <?= $n === $questionNum ? 'scale-current' : '' ?>"
             <?= $n === $questionNum ? 'aria-current="step"' : '' ?>>
          <span class="scale-dot" aria-hidden="true"><?= $n === $questionNum ? '●' : '·' ?></span>
          <span class="scale-q"><?= $p['q'] ?></span>
          <span class="scale-pts"><?= $p['pts'] ?></span>
        </div>
      <?php endforeach; ?>
    </aside>

    <!-- Centre -->
    <section class="game-center" aria-label="Question en cours">

      <!-- Topbar mobile : Q, palier, jokers -->
      <div class="game-topbar" aria-label="État de la partie">
        <span class="game-badge">Q<?= $questionNum ?>/<?= $totalQuestions ?></span>
        <span class="game-badge">Palier : <?= $paliers[$questionNum]['pts'] ?></span>
        <span class="game-badge <?= $fouUsed ? 'game-badge--used' : '' ?>"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i> <?= $fouUsed ? 'Utilisé' : 'Dispo' ?></span>
        <span class="game-badge <?= $pigeonUsed ? 'game-badge--used' : '' ?>"><i class="fa-solid fa-dove" aria-hidden="true"></i> <?= $pigeonUsed ? 'Utilisé' : 'Dispo' ?></span>
      </div>

      <!-- Barre de progression (desktop : "Progression ——— Question 1 / 10") -->
      <div class="game-progress-row" role="progressbar"
           aria-valuenow="<?= $questionNum ?>" aria-valuemin="1" aria-valuemax="<?= $totalQuestions ?>">
        <span class="game-progress-label">Progression</span>
        <div class="game-progress-bar">
          <div class="game-progress-fill" style="width:<?= ($questionNum-1)/$totalQuestions*100 ?>%"></div>
        </div>
        <span class="game-progress-num">Question <?= $questionNum ?> / <?= $totalQuestions ?></span>
      </div>

      <!-- Échelle mobile scrollable -->
      <div class="game-scale-mobile" aria-label="Échelle des paliers">
        <div class="game-scale-row">
          <?php foreach (array_reverse($paliers, true) as $n => $p): ?>
            <span class="scale-pill <?= $n === $questionNum ? 'scale-current' : '' ?>"
                  <?= $n === $questionNum ? 'aria-current="step"' : '' ?>><?= $p['q'] ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if ($lastResult === 'correct' && $lastQ): ?>
        <div class="feedback-box feedback-correct" role="status"><i class="fa-solid fa-check" aria-hidden="true"></i> Bonne réponse !</div>
      <?php endif; ?>

      <!-- Question -->
      <div class="game-question-card">
        <div class="game-q-num">QUESTION <?= $questionNum ?></div>
        <p class="game-q-text" id="question-text"><?= htmlspecialchars($question['question']) ?></p>
      </div>

      <!-- Réponses + jokers + submit -->
      <form method="POST" action="index.php?action=answer" aria-labelledby="question-text">
        <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
        <div class="game-answers" role="group" aria-label="Choisissez une réponse">
          <?php foreach ([1,2,3,4] as $i): ?>
            <label class="answer" id="answer-<?= $i ?>">
              <input type="radio" name="answer" value="<?= $i ?>" class="sr-only"
                     aria-label="Réponse <?= chr(64+$i) ?> : <?= htmlspecialchars($question['answer'.$i]) ?>">
              <span class="answer-letter" aria-hidden="true"><?= chr(64+$i) ?></span>
              <?= htmlspecialchars($question['answer'.$i]) ?>
            </label>
          <?php endforeach; ?>
        </div>

        <!-- Ligne jokers + confirmer -->
        <div class="game-action-row">
          <button type="button" id="btn-fou"
                  class="btn-joker-pill <?= $fouUsed ? 'btn-joker-used' : '' ?>"
                  id="btn-fou-click"
                  <?= $fouUsed ? 'disabled aria-disabled="true"' : '' ?>
                  aria-label="Joker Fou du roi<?= $fouUsed ? ' (déjà utilisé)' : '' ?>">
            <span aria-hidden="true"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i></span> Fou du roi
          </button>
          <button type="button" id="btn-pigeon"
                  class="btn-joker-pill <?= $pigeonUsed ? 'btn-joker-used' : '' ?>"
                  id="btn-pigeon-click"
                  <?= $pigeonUsed ? 'disabled aria-disabled="true"' : '' ?>
                  aria-label="Joker Pigeon au fermier<?= $pigeonUsed ? ' (déjà utilisé)' : '' ?>">
            <span aria-hidden="true"><i class="fa-solid fa-dove" aria-hidden="true"></i></span> Pigeon au fermier
          </button>
          <button type="submit" class="btn-confirm">Confirmer</button>
        </div>
      </form>
    </section>

    <!-- Sidebar jokers -->
    <aside class="game-sidebar" aria-label="Informations de partie">
      <div class="sidebar-section-title">JOKERS</div>
      <div class="sidebar-joker <?= $fouUsed ? 'sidebar-joker-used' : '' ?>">
        <div class="sidebar-joker-icon" aria-hidden="true"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i></div>
        <div class="sidebar-joker-info">
          <strong>Fou du roi</strong>
          <p>Élimine 2 mauvaises réponses</p>
        </div>
        <?= $fouUsed ? '<span class="badge-used">Utilisé</span>' : '<span class="badge-dispo">Dispo</span>' ?>
      </div>
      <div class="sidebar-joker <?= $pigeonUsed ? 'sidebar-joker-used' : '' ?>">
        <div class="sidebar-joker-icon" aria-hidden="true"><i class="fa-solid fa-dove" aria-hidden="true"></i></div>
        <div class="sidebar-joker-info">
          <strong>Pigeon au fermier</strong>
          <p>Demande l'avis d'un expert</p>
        </div>
        <?= $pigeonUsed ? '<span class="badge-used">Utilisé</span>' : '<span class="badge-dispo">Dispo</span>' ?>
      </div>
      <div class="sidebar-section-title game-sidebar-mt">STATISTIQUES</div>
      <div class="sidebar-stats">
        <div class="sidebar-stat">
          <span class="stat-val"><?= (int)($_SESSION['user']['oignons']??0) ?></span>
          <span class="stat-lbl">Oignons</span>
        </div>
        <div class="sidebar-stat">
          <span class="stat-val"><?= $questionNum ?></span>
          <span class="stat-lbl">Question</span>
        </div>
        <div class="sidebar-stat">
          <span class="stat-val"><?= $game['total']>0 ? round($game['correct']/$game['total']*100).'%' : '–' ?></span>
          <span class="stat-lbl">Réussite</span>
        </div>
        <div class="sidebar-stat">
          <span class="stat-val"><?= $game['correct'] ?></span>
          <span class="stat-lbl">Bonnes rép.</span>
        </div>
      </div>
    </aside>

  </div>

<?php else: ?>

  <div class="page-wrap">
    <div class="card game-over-card game-over-card-win">
      <div class="game-over-icon" aria-hidden="true"><i class="fa-solid fa-champagne-glasses"></i></div>
      <h2 class="game-over-title">Incroyable !</h2>
      <p class="game-over-sub">Vous avez répondu correctement aux 10 questions !</p>
      <div class="game-over-score">Score : <strong>10 / 10</strong> — +200 oignons 🧅</div>
      <div class="game-over-actions">
        <a href="index.php?action=endGame" class="btn-primary btn-inline">
          Valider et récupérer mes oignons
        </a>
        <a href="index.php?action=game&restart=1" class="btn-outline btn-inline">Rejouer</a>
      </div>
    </div>
  </div>

<?php endif; ?>

</main>

<script>
/* Données injectées par le serveur — passage PHP <i class="fa-solid fa-arrow-right" aria-hidden="true"></i> JS */
const CORRECT     = <?= $correctAnswer ?>;
const TO_ELIM     = <?= json_encode($toEliminate) ?>;
const FOU_USED    = <?= $fouUsed ? 'true' : 'false' ?>;
const PIGEON_USED = <?= $pigeonUsed ? 'true' : 'false' ?>;
</script>


