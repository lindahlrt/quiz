/* app.js — Scripts globaux */
function toggleMobileMenu() {
  const menu   = document.getElementById('nav-dropdown');
  const burger = document.querySelector('.nav-burger');
  if (!menu) return;
  const isOpen = menu.classList.toggle('open');
  if (burger) burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
}

document.addEventListener('click', function(e) {
  const menu   = document.getElementById('nav-dropdown');
  const burger = document.querySelector('.nav-burger');
  if (!menu || !burger) return;
  if (!menu.contains(e.target) && !burger.contains(e.target)) {
    menu.classList.remove('open');
    burger.setAttribute('aria-expanded', 'false');
  }
});

// Barre de force du mot de passe (inscription)
const pwdInput = document.getElementById('password');
if (pwdInput && document.getElementById('pwd-fill')) {
  pwdInput.addEventListener('input', function () {
    const val = this.value;
    const fill  = document.getElementById('pwd-fill');
    const label = document.getElementById('pwd-label');

    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[\W_]/.test(val))         score++;

    const levels = [
      { pct: 0,   color: '',          text: '' },
      { pct: 20,  color: '#e74c3c',   text: 'Trop court' },
      { pct: 45,  color: '#e74c3c',   text: 'Très faible' },
      { pct: 65,  color: '#e67e22',   text: 'Faible' },
      { pct: 85,  color: '#f1c40f',   text: 'Moyen' },
      { pct: 100, color: '#2ecc71',   text: 'Fort ✓' },
    ];

    let lvlIndex = 0;
    if (val.length === 0) lvlIndex = 0;
    else if (val.length < 8) lvlIndex = 1;
    else lvlIndex = score + 1;
    const lvl = levels[Math.min(lvlIndex, levels.length - 1)];
    fill.style.width = lvl.pct + '%';
    fill.style.background = lvl.color;
    fill.style.transition = 'width .3s, background .3s';
    label.textContent = lvl.text;
    label.style.color  = lvl.color;
  });
}

// Vérification correspondance mots de passe (inscription)
const pwdConfirm = document.getElementById('password_confirm');
if (pwdConfirm && document.getElementById('pwd-confirm-label')) {
  const check = function() {
    const label = document.getElementById('pwd-confirm-label');
    const pwd   = document.getElementById('password').value;
    const conf  = pwdConfirm.value;
    if (conf.length === 0) {
      label.textContent = '';
    } else if (pwd === conf) {
      label.textContent = 'Mots de passe identiques ✓';
      label.style.color = '#2ecc71';
    } else {
      label.textContent = 'Les mots de passe ne correspondent pas';
      label.style.color = '#e74c3c';
    }
  };
  pwdConfirm.addEventListener('input', check);
  document.getElementById('password').addEventListener('input', check);
}
