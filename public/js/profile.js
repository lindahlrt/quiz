/* ===============================
   profile.js — Edition du profil
================================ */
document.addEventListener('DOMContentLoaded', function () {

  // Aperçu pseudo en temps réel
  const pseudoInput   = document.getElementById('pseudo-input');
  const previewName   = document.getElementById('preview-name');
  const previewAvatar = document.getElementById('preview-avatar');

  if (pseudoInput) {
    pseudoInput.addEventListener('input', function () {
      const val = this.value.trim();
      if (previewName) previewName.textContent = val || '...';
      if (previewAvatar && val) {
        const parts = val.split(' ');
        previewAvatar.textContent = (parts[0][0] + (parts[1]?.[0] ?? parts[0][1] ?? '')).toUpperCase();
      }
    });
  }

  // Vérification correspondance mots de passe
  const newPass    = document.getElementById('new-pass');
  const confirmPass = document.getElementById('confirm-pass');
  const passMsg    = document.getElementById('pass-msg');

  function checkPasswords() {
    if (!newPass || !confirmPass || !passMsg) return;
    if (confirmPass.value.length === 0) { passMsg.textContent = ''; return; }
    if (newPass.value === confirmPass.value) {
      passMsg.textContent = '✅ Les mots de passe correspondent';
      passMsg.style.color = 'green';
    } else {
      passMsg.textContent = '❌ Les mots de passe ne correspondent pas';
      passMsg.style.color = '#c0392b';
    }
  }

  if (newPass)    newPass.addEventListener('input', checkPasswords);
  if (confirmPass) confirmPass.addEventListener('input', checkPasswords);
});
