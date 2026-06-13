<footer class="site-footer">
  <p>© Qui veut gagner des oignons — Tous droits d'épluchage réservés</p>
  <p class="footer-legal"><a href="index.php?action=legal">Mentions légales</a></p>
</footer>

<script src="<?= BASE_URL ?>/public/js/app.js"></script>
<script src="<?= BASE_URL ?>/public/js/game.js"></script>
<script src="<?= BASE_URL ?>/public/js/training.js"></script>
<?php if (isset($currentAction) && in_array($currentAction, ['editProfile','showProfile'])): ?>
  <script src="<?= BASE_URL ?>/public/js/profile.js"></script>
<?php endif; ?>
</body>
</html>
