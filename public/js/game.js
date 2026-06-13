/* game.js — Logique du jeu
   CORRECT, TO_ELIM, FOU_USED, PIGEON_USED injectés par PHP */

const phrasesFou = [
  "Ha ! Ces deux-là sont aussi fausses que la barbe du roi !",
  "Même moi je sais que ces deux réponses sont ridicules !",
  "Le fou du roi éclate de rire et chasse deux réponses absurdes !",
  "Même un fou ne tomberait pas dans ce piège ! Adieu ces deux réponses !",
  "Ces deux réponses sentent la bêtise à plein nez ! Je les chasse !",
  "Ces deux réponses sont dignes d'un âne de la cour… dehors !"
];
const msgsPigeonKo = [
  "Le pigeon ne revient pas… Il s'est probablement perdu en route.",
  "Le pigeon est parti… et semble avoir oublié le chemin du retour.",
  "Le pigeon a disparu à l'horizon. Mauvais sens de l'orientation."
];
async function markJokerUsed(joker) {
  try {
    const response = await fetch('index.php?action=useJoker&joker=' + joker);
    if (!response.ok) {
      console.error('Erreur lors de l\'enregistrement du joker :', response.status);
    }
  } catch (error) {
    console.error('Impossible de contacter le serveur :', error);
  }
}

function selectAnswer(label, val) {
  document.querySelectorAll('.answer').forEach(a => a.classList.remove('selected'));
  label.classList.add('selected');
  const input = label.querySelector('input');
  if (input) input.checked = true;
}

function showOverlay(icon, msg) {
  const overlay = document.getElementById('joker-overlay');
  document.getElementById('joker-overlay-icon').innerHTML = icon;
  document.getElementById('joker-overlay-msg').innerHTML    = msg;
  if (overlay) { overlay.style.display = 'flex'; overlay.focus(); }
}

function closeJokerOverlay() {
  const el = document.getElementById('joker-overlay');
  if (el) el.style.display = 'none';
}

function useFou() {
  if (typeof FOU_USED !== 'undefined' && FOU_USED) return;
  const phrase = phrasesFou[Math.floor(Math.random() * phrasesFou.length)];
  TO_ELIM.forEach(i => {
    const el = document.getElementById('answer-' + i);
    if (el) {
      el.classList.add('answer-eliminated');
      const input = el.querySelector('input');
      if (input) input.disabled = true;
    }
  });
  const btn = document.getElementById('btn-fou');
  if (btn) { btn.disabled = true; btn.classList.add('btn-joker-used'); }
  showOverlay('🃏', phrase);
  markJokerUsed('fou');
}

function usePigeon() {
  if (typeof PIGEON_USED !== 'undefined' && PIGEON_USED) return;
  const revient = Math.random() < 0.5;
  let msg;
  if (revient) {
    msg = 'Le pigeon revient avec un message du fermier…<br><small>Le fermier suggère la réponse <strong>'
        + String.fromCharCode(64 + CORRECT) + '</strong>.</small>';
    const el = document.getElementById('answer-' + CORRECT);
    if (el) el.classList.add('answer-suggested');
  } else {
    msg = msgsPigeonKo[Math.floor(Math.random() * msgsPigeonKo.length)];
  }
  const btn = document.getElementById('btn-pigeon');
  if (btn) { btn.disabled = true; btn.classList.add('btn-joker-used'); }
  showOverlay('🕊️', msg);
  markJokerUsed('pigeon');
}

// Attacher les événements (script chargé en bas de page, DOM déjà prêt)
{
  const btnClose = document.getElementById('btn-close-overlay');
  if (btnClose) btnClose.addEventListener('click', closeJokerOverlay);

  const btnFouGame = document.getElementById('btn-fou');
  if (btnFouGame) btnFouGame.addEventListener('click', useFou);

  const btnPigeonGame = document.getElementById('btn-pigeon');
  if (btnPigeonGame) btnPigeonGame.addEventListener('click', usePigeon);

  [1, 2, 3, 4].forEach(i => {
    const label = document.getElementById('answer-' + i);
    if (label) label.addEventListener('click', function () {
      selectAnswer(this, i);
    });
  });
}
