<main id="main-content">

<div class="page-wrap">
  <div class="ranking-container">

    <section class="ranking-main card" aria-labelledby="ranking-general-title">
      <div class="ranking-header">
        <div class="card-label" id="ranking-general-title">CLASSEMENT GÉNÉRAL</div>
        <div class="ranking-tabs">
          <span class="ranking-tab active">Global</span>
        </div>
      </div>

      <?php if (empty($ranking)): ?>
        <div class="empty-state">Aucun joueur classé pour l'instant.<br>Jouez une partie pour apparaître ici ! 🧅</div>
      <?php else: ?>
        <ol class="ranking-list">
          <?php foreach ($ranking as $i => $player): ?>
            <li class="ranking-row <?= $player['id'] == $_SESSION['user']['id'] ? 'ranking-me' : '' ?>">
              <span class="ranking-rank" aria-label="Position <?= $i+1 ?>"><?= $i+1 ?></span>
              <div class="ranking-avatar" style="background:<?= avatarColor($i) ?>" aria-hidden="true"><?= initials($player['pseudo']) ?></div>
              <span class="ranking-name">
                <?= htmlspecialchars($player['pseudo']) ?>
                <?php if ($player['id'] == $_SESSION['user']['id']): ?><span class="badge-vous">Vous</span><?php endif; ?>
              </span>
              <span class="ranking-score"><?= (int)$player['oignons'] ?> 🧅</span>
            </li>
          <?php endforeach; ?>
        </ol>
      <?php endif; ?>
    </section>

    <aside class="ranking-sidebar" aria-label="Podium et votre position">
      <section class="card ranking-podium-card" aria-labelledby="podium-title">
        <div class="card-label" id="podium-title">PODIUM</div>
        <?php if (count($top3) >= 1): ?>
        <ol class="podium" aria-label="Top 3 joueurs">
          <?php if (isset($top3[1])): ?>
          <li class="podium-player podium-2" value="2">
            <div class="podium-avatar" style="background:<?= avatarColor(1) ?>" aria-hidden="true"><?= initials($top3[1]['pseudo']) ?></div>
            <div class="podium-name"><?= htmlspecialchars($top3[1]['pseudo']) ?></div>
            <div class="podium-score"><?= $top3[1]['oignons'] ?> 🧅</div>
            <div class="podium-block p2" aria-hidden="true">2</div>
          </li>
          <?php endif; ?>
          <li class="podium-player podium-1" value="1">
            <div class="podium-avatar" style="background:<?= avatarColor(0) ?>" aria-hidden="true"><?= initials($top3[0]['pseudo']) ?></div>
            <div class="podium-name"><?= htmlspecialchars($top3[0]['pseudo']) ?></div>
            <div class="podium-score"><?= $top3[0]['oignons'] ?> 🧅</div>
            <div class="podium-block p1" aria-hidden="true">1</div>
          </li>
          <?php if (isset($top3[2])): ?>
          <li class="podium-player podium-3" value="3">
            <div class="podium-avatar" style="background:<?= avatarColor(2) ?>" aria-hidden="true"><?= initials($top3[2]['pseudo']) ?></div>
            <div class="podium-name"><?= htmlspecialchars($top3[2]['pseudo']) ?></div>
            <div class="podium-score"><?= $top3[2]['oignons'] ?> 🧅</div>
            <div class="podium-block p3" aria-hidden="true">3</div>
          </li>
          <?php endif; ?>
        </ol>
        <?php else: ?>
          <div class="empty-state ranking-empty">Aucun joueur encore 🧅</div>
        <?php endif; ?>
      </section>

      <section class="card ranking-sidebar-mt" aria-labelledby="my-position-title">
        <div class="card-label" id="my-position-title">VOTRE POSITION</div>
        <div class="my-position">
          <span class="my-pos-rank" aria-label="Position <?= $myRank ?>"><?= $myRank ?></span>
          <div class="ranking-avatar ranking-avatar-me" aria-hidden="true"><?= initials($_SESSION['user']['pseudo']) ?></div>
          <div>
            <strong><?= htmlspecialchars($_SESSION['user']['pseudo']) ?></strong>
            <p class="my-pos-oignons"><?= $myData['oignons'] ?? 0 ?> oignons récoltés</p>
          </div>
          <span class="badge-vous">Vous</span>
        </div>
        <?php if ($nextUser): ?>
        <div class="ranking-progress-mt">
          <p class="ranking-progress-label">Progression vers le top <?= $myRank-1 ?></p>
          <?php $pct = $nextUser['oignons'] > 0 ? min(100, round(($myData['oignons']??0) / $nextUser['oignons'] * 100)) : 100; ?>
          <div class="game-progress-bar" role="progressbar" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100">
            <div class="game-progress-fill" style="width:<?= $pct ?>%"></div>
          </div>
          <p class="ranking-progress-hint"><?= $oignonsManquants ?> oignons manquants</p>
        </div>
        <?php endif; ?>
      </section>
    </aside>

  </div>
</div>

</main>
