<?php
$editQ = null;
if (isset($_GET['edit'])) {
    $editQ = getQuestionById((int)$_GET['edit']);
}
?>

<main id="main-content">
<div class="page-wrap">
<div class="admin-container">

  <div class="admin-dashboard-header">
    <div class="admin-dashboard-title">
      <h1>Dashboard</h1>
      <p>Panneau d'administration</p>
    </div>
    <a href="index.php?action=showProfile" class="admin-back-link"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour au profil</a>
  </div>

  <nav class="admin-tabs" aria-label="Navigation admin">
    <a href="index.php?action=admin" class="admin-tab admin-tab-active"><i class="fa-solid fa-gear" aria-hidden="true"></i> Questions <span class="admin-tab-badge"><?= $totalQ ?></span></a>
    <a href="index.php?action=adminUsers" class="admin-tab"><i class="fa-solid fa-users" aria-hidden="true"></i> Utilisateurs</a>
  </nav>

  <div class="admin-grid">

    <section class="card admin-form-card" aria-labelledby="admin-form-title">
      <h2 class="card-label" id="admin-form-title"><?= $editQ ? 'MODIFIER LA QUESTION' : 'AJOUTER UNE QUESTION' ?></h2>

      <form method="POST" action="index.php?action=<?= $editQ ? 'editQuestion' : 'addQuestion' ?>" class="admin-form">
        <?php if ($editQ): ?>
          <input type="hidden" name="id" value="<?= $editQ['id'] ?>">
        <?php endif; ?>

        <div class="auth-field">
          <label for="admin-question">Question</label>
          <textarea id="admin-question" name="question" rows="3" required class="admin-textarea"><?= $editQ ? htmlspecialchars($editQ['question']) : '' ?></textarea>
        </div>

        <?php foreach ([1,2,3,4] as $i): ?>
          <div class="auth-field admin-field-relative">
            <label for="admin-answer<?= $i ?>">
              Réponse <?= chr(64+$i) ?>
              <span class="admin-answer-hint">— sélectionnez la bonne réponse ci-dessous</span>
            </label>
            <div class="admin-answer-row">
              <input type="text" id="admin-answer<?= $i ?>" name="answer<?= $i ?>" required
                value="<?= $editQ ? htmlspecialchars($editQ['answer'.$i]) : '' ?>"
                placeholder="Réponse <?= chr(64+$i) ?>" class="admin-answer-input">
              <label class="admin-answer-label">
                <input type="radio" name="correct_answer" value="<?= $i ?>"
                  <?= ($editQ && (int)$editQ['correct_answer']===$i) ? 'checked' : '' ?> required>
                <i class="fa-solid fa-check" aria-hidden="true"></i> Correcte
              </label>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="admin-form-actions">
          <button type="submit" class="btn-save"><?= $editQ ? 'Enregistrer les modifications' : 'Ajouter la question' ?></button>
          <?php if ($editQ): ?>
            <a href="index.php?action=admin" class="btn-outline">Annuler</a>
          <?php endif; ?>
        </div>
      </form>
    </section>

    <section class="admin-list-col" aria-labelledby="admin-list-title">
      <h2 class="card-label admin-list-title" id="admin-list-title">QUESTIONS EXISTANTES</h2>
      <?php if (empty($questions)): ?>
        <div class="card admin-empty">
          Aucune question pour l'instant.<br>Ajoutez-en une à gauche !
        </div>
      <?php else: ?>
        <?php foreach ($questions as $q): ?>
          <article class="card admin-question-card <?= ($editQ && $editQ['id']===$q['id']) ? 'admin-question-active' : '' ?>">
            <header class="admin-q-header">
              <span class="admin-q-id">#<?= $q['id'] ?></span>
              <div class="admin-q-actions">
                <a href="index.php?action=admin&edit=<?= $q['id'] ?>" class="admin-btn-edit"><i class="fa-solid fa-pen" aria-hidden="true"></i> Modifier</a>
                <a href="index.php?action=deleteQuestion&id=<?= $q['id'] ?>"
                   class="admin-btn-delete"
                   onclick="return confirm('Supprimer cette question ?')"><i class="fa-solid fa-trash" aria-hidden="true"></i> Supprimer</a>
              </div>
            </header>
            <p class="admin-q-text"><?= htmlspecialchars($q['question']) ?></p>
            <ul class="admin-answers-preview admin-answers-list">
              <?php foreach ([1,2,3,4] as $i): ?>
                <li class="admin-answer-pill <?= (int)$q['correct_answer']===$i ? 'admin-answer-correct' : '' ?>">
                  <?= chr(64+$i) ?>. <?= htmlspecialchars(mb_strimwidth($q['answer'.$i],0,30,'…')) ?>
                  <?= (int)$q['correct_answer']===$i ? ' <i class="fa-solid fa-check" aria-hidden="true"></i>' : '' ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

  </div>
</div>
</div>
</main>
