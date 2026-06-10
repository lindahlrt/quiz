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
    <a href="index.php?action=admin" class="admin-tab"><i class="fa-solid fa-gear" aria-hidden="true"></i> Questions</a>
    <a href="index.php?action=adminUsers" class="admin-tab admin-tab-active"><i class="fa-solid fa-users" aria-hidden="true"></i> Utilisateurs <span class="admin-tab-badge"><?= $totalU ?></span></a>
  </nav>

  <section class="card" aria-labelledby="users-list-title">
    <h2 class="card-label" id="users-list-title">LISTE DES UTILISATEURS</h2>

    <?php if (empty($users)): ?>
      <div class="admin-empty">Aucun utilisateur enregistré.</div>
    <?php else: ?>
      <table class="admin-users-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Oignons</th>
            <th>Parties</th>
            <th>Inscrit le</th>
            <th>Admin</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr class="<?= $u['id'] == $_SESSION['user']['id'] ? 'admin-row-me' : '' ?>">
              <td class="admin-td-id"><?= $u['id'] ?></td>
              <td><strong><?= htmlspecialchars($u['pseudo']) ?></strong></td>
              <td class="admin-td-email"><?= htmlspecialchars($u['email']) ?></td>
              <td><?= (int)$u['oignons'] ?> 🧅</td>
              <td><?= (int)$u['games_played'] ?></td>
              <td><time datetime="<?= $u['created_at'] ?>"><?= date('d/m/Y', strtotime($u['created_at'])) ?></time></td>
              <td>
                <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                  <?php if ($u['is_admin']): ?>
                    <a href="index.php?action=toggleAdmin&id=<?= $u['id'] ?>&is_admin=0"
                       class="admin-badge-admin"
                       onclick="return confirm('Retirer les droits admin à <?= htmlspecialchars($u['pseudo']) ?> ?')">
                      <i class="fa-solid fa-check" aria-hidden="true"></i> Admin
                    </a>
                  <?php else: ?>
                    <a href="index.php?action=toggleAdmin&id=<?= $u['id'] ?>&is_admin=1"
                       class="admin-badge-user">
                      Promouvoir
                    </a>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="admin-badge-admin">Vous</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                  <a href="index.php?action=deleteUser&id=<?= $u['id'] ?>"
                     class="admin-btn-delete"
                     onclick="return confirm('Supprimer <?= htmlspecialchars($u['pseudo']) ?> ? Cette action est irréversible.')">
                    <i class="fa-solid fa-trash" aria-hidden="true"></i> Supprimer
                  </a>
                <?php else: ?>
                  <span class="admin-td-me">Votre compte</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>

</div>
</div>
</main>
