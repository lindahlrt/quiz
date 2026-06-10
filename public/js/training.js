/* training.js — T_CORRECT, T_TO_ELIM, T_FOU_USED, T_PIGEON_USED injectés par PHP */

const phrasesFouT = [
  "Ha ! Ces deux-là sont aussi fausses que la barbe du roi !",
  "Même moi je sais que ces deux réponses sont ridicules !",
  "Le fou du roi éclate de rire et chasse deux réponses absurdes !"
];
const msgsPigeonKoT = [
  "Le pigeon ne revient pas… Il s'est probablement perdu en route.",
  "Le pigeon est parti… et semble avoir oublié le chemin du retour."
];
async function markTrainingJoker(joker) {
  try {
    const response = await fetch('index.php?action=useTrainingJoker&joker=' + joker);
    if (!response.ok) {
      console.error('Erreur lors de l\'enregistrement du joker :', response.status);
    }
  } catch (error) {
    console.error('Impossible de contacter le serveur :', error);
  }
}

function selectAnswerT(label, val) {
  document.querySelectorAll('.answer').forEach(a => a.classList.remove('selected'));
  label.classList.add('selected');
  const input = label.querySelector('input');
  if (input) input.checked = true;
}

function showOverlay(icon, msg) {
  document.getElementById('joker-overlay-icon').textContent = icon;
  document.getElementById('joker-overlay-msg').innerHTML    = msg;
  const overlay = document.getElementById('joker-overlay');
  if (overlay) overlay.style.display = 'flex';
}

function closeJokerOverlay() {
  const el = document.getElementById('joker-overlay');
  if (el) el.style.display = 'none';
}

function useFouT() {
  if (typeof T_FOU_USED !== 'undefined' && T_FOU_USED) return;
  const phrase = phrasesFouT[Math.floor(Math.random() * phrasesFouT.length)];
  T_TO_ELIM.forEach(i => {
    const el = document.getElementById('answer-t-' + i);
    if (el) {
      el.classList.add('answer-eliminated');
      const input = el.querySelector('input');
      if (input) input.disabled = true;
    }
  });
  const btn = document.getElementById('btn-t-fou');
  if (btn) { btn.disabled = true; btn.classList.add('btn-joker-used'); }
  showOverlay('🃏', phrase);
  markTrainingJoker('fou');
}

function usePigeonT() {
  if (typeof T_PIGEON_USED !== 'undefined' && T_PIGEON_USED) return;
  const revient = Math.random() < 0.5;
  let msg;
  if (revient) {
    msg = 'Le pigeon revient…<br><small>Le fermier suggère la réponse <strong>'
        + String.fromCharCode(64 + T_CORRECT) + '</strong>.</small>';
    const el = document.getElementById('answer-t-' + T_CORRECT);
    if (el) el.classList.add('answer-suggested');
  } else {
    msg = msgsPigeonKoT[Math.floor(Math.random() * msgsPigeonKoT.length)];
  }
  const btn = document.getElementById('btn-t-pigeon');
  if (btn) { btn.disabled = true; btn.classList.add('btn-joker-used'); }
  showOverlay('🕊️', msg);
  markTrainingJoker('pigeon');
}
