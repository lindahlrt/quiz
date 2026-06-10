<main id="main-content">
<div class="page-wrap">

  <section class="home-hero" aria-label="Présentation du jeu">
    <h1>Bienvenue dans<br><span>Qui veut gagner des oignons</span> <img src="<?= BASE_URL ?>/public/images/onion_transparent.webp" alt="Mascotte oignon du quiz" title="Mascotte oignon du quiz" width="40" height="37" class="hero-onion"></h1>
    <p>Inspiré du célèbre jeu télévisé, ce quiz vous met au défi de répondre à des questions de plus en plus difficiles. À chaque bonne réponse, vous progressez... mais attention aux jokers peu fiables.</p>
    <?php if ($lastGame): ?>
      <p class="hero-last-game">
        Dernière partie : <?= $lastGame['correct_answers'] ?>/<?= $lastGame['total_questions'] ?> — <?= $lastGame['oignons_earned'] ?> 🧅
      </p>
    <?php endif; ?>
    <div class="home-hero-btns">
      <a href="index.php?action=game" class="btn-hero-play"><i class="fa-solid fa-play" aria-hidden="true"></i> Jouer maintenant</a>
      <a href="index.php?action=training" class="btn-hero-train"><i class="fa-solid fa-carrot" aria-hidden="true"></i> S'entraîner</a>
    </div>
  </section>

  <?php if ($myStats): ?>
  <section class="home-stats" aria-label="Vos statistiques">
    <div class="home-stat-card">
      <div class="home-stat-val"><?= (int)$myStats['games_played'] ?></div>
      <div class="home-stat-label">Parties jouées</div>
    </div>
    <div class="home-stat-card">
      <div class="home-stat-val"><?= (int)$myStats['oignons'] ?></div>
      <div class="home-stat-label">Oignons récoltés</div>
    </div>
    <div class="home-stat-card">
      <div class="home-stat-val"><?= round($myStats['win_rate']) ?>%</div>
      <div class="home-stat-label">Taux de réussite</div>
    </div>
    <div class="home-stat-card">
      <div class="home-stat-val">#<?= $position ?></div>
      <div class="home-stat-label">Classement</div>
    </div>
  </section>
  <?php endif; ?>

  <div class="home-bottom-grid">
    <div>
      <section class="card home-section" aria-labelledby="how-title">
        <h2 class="card-label" id="how-title">Comment ça marche</h2>
        <p class="home-section-text">
          À chaque bonne réponse, vous <strong>progressez</strong> dans l'échelle des oignons.
          Plus vous avancez, plus les questions deviennent piégeuses... et vos <strong>jokers</strong> de moins en moins fiables.
        </p>

        <h3 class="card-label"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i> Les jokers</h3>
        <ul class="jokers-list" aria-label="Liste des jokers">
          <li class="joker-item">
            <span class="joker-icon" aria-hidden="true"><i class="fa-solid fa-chess-knight" aria-hidden="true"></i></span>
            <div>
              <strong>Fou du roi</strong>
              <p>Élimine deux mauvaises réponses avec plus ou moins de sérieux.</p>
            </div>
          </li>
          <li class="joker-item">
            <span class="joker-icon" aria-hidden="true"><i class="fa-solid fa-dove" aria-hidden="true"></i></span>
            <div>
              <strong>Pigeon au fermier</strong>
              <p>Envoyez un message à Fernand… en espérant que le pigeon arrive.</p>
            </div>
          </li>
        </ul>

        <div class="warning-box" role="note">
          <h3 class="card-label text-warning"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Avertissements</h3>
          <ul class="home-warning-list">
            <li><i class="fa-solid fa-circle-xmark" aria-hidden="true"></i> Le fermier n'est pas toujours disponible</li>
            <li><i class="fa-solid fa-circle-xmark" aria-hidden="true"></i> Ses réponses ne sont pas toujours fiables</li>
            <li><i class="fa-solid fa-circle-xmark" aria-hidden="true"></i> Le pigeon n'a pas le meilleur sens de l'orientation</li>
          </ul>
        </div>
      </section>
    </div>

    <div>
      <section class="card home-section" aria-labelledby="ranking-title">
        <h2 class="card-label" id="ranking-title">Classement — Top 3</h2>
        <?php if (empty($top3)): ?>
          <div class="empty-state">Aucun joueur classé pour l'instant.<br>Soyez le premier ! 🧅</div>
        <?php else: ?>
          <ol class="top3-list">
            <?php foreach ($top3 as $i => $player): ?>
              <li class="top3-row <?= $i===0 ? 'top3-first' : '' ?>">
                <span class="top3-rank" aria-label="Position <?= $i+1 ?>"><?= $i+1 ?></span>
                <div class="top3-avatar" style="background:<?= avatarColor($i) ?>" aria-hidden="true">
                  <?= initials($player['pseudo']) ?>
                </div>
                <span class="top3-name"><?= htmlspecialchars($player['pseudo']) ?></span>
                <span class="top3-score"><?= (int)$player['oignons'] ?> 🧅</span>
              </li>
            <?php endforeach; ?>
          </ol>
          <a href="index.php?action=ranking" class="top3-link">Voir tout le classement <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        <?php endif; ?>
      </section>
    </div>
  </div>

</div>
</main>
