<footer class="site-footer">
  <p>© Qui veut gagner des oignons — Tous droits d'épluchage réservés</p>
  <p class="footer-legal"><a href="index.php?action=legal">Mentions légales</a></p>
</footer>

<script src="<?= BASE_URL ?>/public/js/app.js"></script>
<?php if (isset($currentAction) && $currentAction === 'game'): ?>
  <script src="<?= BASE_URL ?>/public/js/game.js"></script>
<?php endif; ?>
<?php if (isset($currentAction) && $currentAction === 'training'): ?>
  <script src="<?= BASE_URL ?>/public/js/training.js"></script>
<?php endif; ?>
<?php if (isset($currentAction) && in_array($currentAction, ['editProfile','showProfile'])): ?>
  <script src="<?= BASE_URL ?>/public/js/profile.js"></script>
<?php endif; ?>
</body>
</html>
