<main id="main-content">

<div class="page-wrap">
  <div class="profile-layout">

    <div class="profile-left">
      <div class="card profile-card">
        <div class="profile-avatar-lg"><?= initials($stats['pseudo'] ?? 'JD') ?></div>
        <h2 class="profile-name"><?= htmlspecialchars($stats['pseudo'] ?? '') ?></h2>
        <p class="profile-since">Membre depuis <?= date('F Y', strtotime($stats['created_at'] ?? 'now')) ?></p>
        <a href="index.php?action=editProfile" class="btn-outline">Modifier le profil</a>
        <?php if (!empty($_SESSION['user']['is_admin'])): ?>
          <a href="index.php?action=admin" class="btn-outline btn-admin-link"><i class="fa-solid fa-gear" aria-hidden="true"></i> Dashboard</a>
        <?php endif; ?>
      </div>

      <section class="card profile-stats-section" aria-labelledby="stats-title">
        <div class="card-label" id="stats-title">STATISTIQUES</div>
        <div class="profile-stats-grid">
          <div class="profile-stat"><span class="profile-stat-val"><?= (int)$stats['games_played'] ?></span><span class="profile-stat-lbl">Parties</span></div>
          <div class="profile-stat"><span class="profile-stat-val"><?= $oignons ?></span><span class="profile-stat-lbl">Oignons</span></div>
          <div class="profile-stat"><span class="profile-stat-val"><?= round($stats['win_rate'] ?? 0) ?>%</span><span class="profile-stat-lbl">Réussite</span></div>
          <div class="profile-stat"><span class="profile-stat-val">#<?= $myRank ?></span><span class="profile-stat-lbl">Classement</span></div>
        </div>
      </section>

      <section class="card profile-stats-section" aria-labelledby="trophees-title">
        <div class="card-label" id="trophees-title">TROPHÉES</div>
        <ul class="trophees-grid" role="list">
          <li class="trophee <?= $stats['games_played']>=1?'earned':'locked' ?>">
            <img src="<?= BASE_URL ?>/public/images/trophee_1.webp" alt="Trophée 1ère victoire" title="1ère victoire" class="trophee-img">
            <small>1ère victoire</small>
          </li>
          <li class="trophee <?= $stats['games_played']>=5?'earned':'locked' ?>">
            <img src="<?= BASE_URL ?>/public/images/trophee_2.webp" alt="Trophée 5 victoires" title="5 victoires" class="trophee-img">
            <small>5 victoires</small>
          </li>
          <li class="trophee <?= $oignons>=100?'earned':'locked' ?>">
            <img src="<?= BASE_URL ?>/public/images/trophee_3.webp" alt="Trophée 100 oignons récoltés" title="100 oignons" class="trophee-img">
            <small>100 oignons</small>
          </li>
          <li class="trophee <?= $myRank<=3?'earned':'locked' ?>">
            <img src="<?= BASE_URL ?>/public/images/trophee_4.webp" alt="Trophée Top 3 du classement" title="Top 3" class="trophee-img">
            <small>Top 3</small>
          </li>
          <li class="trophee locked">
            <img src="<?= BASE_URL ?>/public/images/trophee_5.webp" alt="Trophée score parfait" title="Score parfait" class="trophee-img">
            <small>Parfait</small>
          </li>
        </ul>
      </section>
    </div>

    <div class="profile-right">
      <section class="card" aria-labelledby="history-title">
        <div class="card-label" id="history-title">HISTORIQUE DES PARTIES</div>
        <?php if (empty($history)): ?>
          <div class="empty-state">Aucune partie jouée pour l'instant.<br><a href="index.php?action=game" class="legal-link">Jouer maintenant <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div>
        <?php else: ?>
          <ol class="history-list">
          <?php foreach ($history as $g):
            $isWin = $g['correct_answers'] >= $g['total_questions'] / 2;
            $res = $isWin ? 'V' : 'D';
          ?>
            <li class="history-row <?= $isWin ? 'history-win' : 'history-lose' ?>">
              <span class="history-badge"><?= $res ?></span>
              <div class="history-info">
                <strong><?= $g['correct_answers'] ?> / <?= $g['total_questions'] ?><?= $g['correct_answers']==$g['total_questions'] ? ' — Score parfait' : '' ?></strong>
                <small><time datetime="<?= $g['played_at'] ?>"><?= date('d/m/Y à H\hi', strtotime($g['played_at'])) ?></time></small>
              </div>
              <span class="history-pts">+<?= $g['oignons_earned'] ?> 🧅</span>
            </li>
          <?php endforeach; ?>
          </ol>
        <?php endif; ?>
      </section>

      <section class="card profile-stats-section" aria-labelledby="progression-title">
        <div class="card-label" id="progression-title">PROGRESSION VERS LE PROCHAIN PALIER</div>
        <div class="progression-labels">
          <span><?= $oignons ?> oignons</span>
          <?php if ($oignonsManquants > 0): ?>
            <span class="progression-manquants"><?= $oignonsManquants ?> manquants</span>
            <span><?= $oignonsNext ?> oignons (top <?= max(1,$myRank-1) ?>)</span>
          <?php else: ?>
            <span class="progression-leader"><i class="fa-solid fa-trophy" aria-hidden="true"></i> Vous êtes en tête !</span>
          <?php endif; ?>
        </div>
        <div class="game-progress-bar profile-progress-bar" role="progressbar"
             aria-valuenow="<?= $pctProg ?>" aria-valuemin="0" aria-valuemax="100"
             aria-label="Progression vers le rang supérieur">
          <div class="game-progress-fill" style="width:<?= $pctProg ?>%"></div>
        </div>
      </section>
    </div>

  </div>
</div>

</main>
