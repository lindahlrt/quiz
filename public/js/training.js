// ══════════════════════════════════════════════
// MODE ENTRAÎNEMENT — Fetch API côté client
// ══════════════════════════════════════════════

let currentQuestion = null;
let btn = null;
let selectedAnswer  = null;
let stats = { correct: 0, total: 0 };
const difficulty    = window.TRAINING_DIFFICULTY || 'easy';
let questionBuffer  = [];

// ── Chargement questions depuis OpenTDB ────────
async function loadQuestions() {
  try {
    const url  = `https://opentdb.com/api.php?amount=10&difficulty=${difficulty}&type=multiple&encode=url3986`;
    const res  = await fetch(url);
    const data = await res.json();
    if (data.response_code !== 0 || !data.results.length) throw new Error('API error');

    questionBuffer = data.results.map(q => {
      const correct    = decodeURIComponent(q.correct_answer);
      const incorrects = q.incorrect_answers.map(decodeURIComponent);
      const answers    = [correct, ...incorrects].sort(() => Math.random() - 0.5);
      return {
        question       : decodeURIComponent(q.question),
        category       : decodeURIComponent(q.category),
        answers,
        correctAnswer  : answers.indexOf(correct) + 1,
      };
    });
  } catch (e) {
    showApiError();
  }
}

// ── Afficher la question suivante ──────────────
async function nextQuestion() {
  if (!questionBuffer.length) await loadQuestions();
  if (!questionBuffer.length) { showApiError(); return; }

  currentQuestion = questionBuffer.shift();
  selectedAnswer  = null;

  // Mise à jour DOM
  document.getElementById('training-category').textContent  = '📚 ' + currentQuestion.category;
  document.getElementById('training-q-text').textContent    = currentQuestion.question;
  document.getElementById('training-q-num').textContent     = 'ENTRAÎNEMENT — QUESTION ' + (stats.total + 1);
  document.getElementById('training-feedback').style.display = 'none';
  document.getElementById('training-confirm').disabled = true;
  document.getElementById('training-confirm').dataset.state = 'confirm';

  // Réponses
  [1,2,3,4].forEach(i => {
    const label = document.getElementById('answer-t-' + i);
    label.classList.remove('selected','correct','wrong','answer-eliminated');
    label.querySelector('.answer-text').textContent = currentQuestion.answers[i - 1];
    label.querySelector('input').value = i;
    label.style.opacity = '1';
    label.style.pointerEvents = 'auto';
  });

  // Barre progression
  updateProgress();
}

// ── Sélection d'une réponse ────────────────────
function selectAnswerT(label, num) {
  if (document.getElementById('training-feedback').style.display !== 'none') return;
  document.querySelectorAll('.answer').forEach(l => l.classList.remove('selected'));
  label.classList.add('selected');
  selectedAnswer = num;
  document.getElementById('training-confirm').disabled = false;
}

// ── Confirmer la réponse ───────────────────────
async function confirmAnswer() {
  const confirmBtn = document.getElementById('training-confirm');

  // Si on est en mode "Question suivante"
  if (confirmBtn && confirmBtn.dataset.state === 'next') {
    confirmBtn.dataset.state = 'confirm';
    confirmBtn.textContent = 'Confirmer';
    confirmBtn.disabled = true;
    nextQuestion();
    return;
  }

  if (!selectedAnswer || !currentQuestion) return;

  const isCorrect = selectedAnswer === currentQuestion.correctAnswer;
  stats.total++;
  if (isCorrect) stats.correct++;

  // Feedback visuel
  [1,2,3,4].forEach(i => {
    const label = document.getElementById('answer-t-' + i);
    if (i === currentQuestion.correctAnswer) label.classList.add('correct');
    else if (i === selectedAnswer)           label.classList.add('wrong');
    label.style.pointerEvents = 'none';
  });

  const feedback = document.getElementById('training-feedback');
  feedback.className = 'feedback-box ' + (isCorrect ? 'feedback-correct' : 'feedback-wrong');
  feedback.innerHTML = isCorrect
    ? '<i class="fa-solid fa-check" aria-hidden="true"></i> Bonne réponse !'
    : '<i class="fa-solid fa-xmark" aria-hidden="true"></i> Mauvaise réponse. La bonne réponse était : <strong>'
      + currentQuestion.answers[currentQuestion.correctAnswer - 1] + '</strong>';
  feedback.style.display = 'block';

  // Mise à jour stats DOM
  const winrate = stats.total > 0 ? Math.round(stats.correct / stats.total * 100) + '%' : '0%';
  const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  setEl('stat-correct', stats.correct);
  setEl('stat-total', stats.total);
  setEl('stat-wrong', stats.total - stats.correct);
  setEl('stat-winrate', winrate);
  setEl('stat-correct-sidebar', stats.correct);
  setEl('stat-total-sidebar', stats.total);
  setEl('stat-wrong-sidebar', stats.total - stats.correct);
  setEl('stat-winrate-sidebar', winrate);

  const confirmBtnEnd = document.getElementById('training-confirm');
  if (confirmBtnEnd) {
    confirmBtnEnd.textContent = 'Question suivante';
    confirmBtnEnd.dataset.state = 'next';
  }

  // Sauvegarder stats en session PHP
  try {
    await fetch(`index.php?action=saveTrainingStat&correct=${isCorrect ? 1 : 0}`);
  } catch(e) {}

  updateProgress();
}

// ── Fou du roi ─────────────────────────────────
const phrasesFouT = [
  "Ha ! Ces deux-là sont aussi fausses que la barbe du roi !",
  "Même moi je sais que ces deux réponses sont ridicules !",
  "Le fou du roi éclate de rire et chasse deux réponses absurdes !",
  "Ces deux réponses sentent la bêtise à plein nez ! Je les chasse !",
];

function setJokerUsedSidebar(joker) {
  const id = joker === 'fou' ? 'sidebar-joker-fou' : 'sidebar-joker-pigeon';
  const el = document.getElementById(id);
  if (!el) return;
  const badge = el.querySelector('.badge-dispo');
  if (badge) { badge.textContent = 'Utilisé'; badge.classList.add('badge-used'); }
}

async function useFouT() {
  if (!currentQuestion) return;
  const wrong = [1,2,3,4].filter(i => i !== currentQuestion.correctAnswer);
  wrong.sort(() => Math.random() - 0.5).slice(0, 2).forEach(i => {
    const label = document.getElementById('answer-t-' + i);
    label.classList.add('answer-eliminated');
    label.style.opacity = '0.25';
    label.style.pointerEvents = 'none';
  });
  document.getElementById('btn-t-fou').disabled = true;
  document.getElementById('btn-t-fou').classList.add('btn-joker-used');
  setJokerUsedSidebar('fou');
  const phrase = phrasesFouT[Math.floor(Math.random() * phrasesFouT.length)];
  showOverlay('<i class="fa-solid fa-chess-knight"></i>', phrase);
  try { await fetch('index.php?action=useTrainingJoker&joker=fou'); } catch(e) {}
}

// ── Pigeon au fermier ──────────────────────────
async function usePigeonT() {
  if (!currentQuestion) return;
  const rand = Math.floor(Math.random() * 4) + 1;
  document.querySelectorAll('.answer').forEach(l => l.classList.remove('selected'));
  document.getElementById('answer-t-' + rand).classList.add('selected');
  selectedAnswer = rand;
  document.getElementById('training-confirm').disabled = false;
  document.getElementById('btn-t-pigeon').disabled = true;
  document.getElementById('btn-t-pigeon').classList.add('btn-joker-used');
  setJokerUsedSidebar('pigeon');
  showOverlay('<i class="fa-solid fa-dove"></i>', 'Le pigeon suggère la réponse ' + String.fromCharCode(64 + rand) + '... mais est-il fiable ?');
  try { await fetch('index.php?action=useTrainingJoker&joker=pigeon'); } catch(e) {}
}

// ── Progression ────────────────────────────────
function updateProgress() {
  const pct = Math.min(100, (stats.total % 10) * 10);
  const fill = document.getElementById('training-progress-fill');
  if (fill) fill.style.width = pct + '%';
  const qNumEl = document.getElementById('training-q-num-badge');
  if (qNumEl) qNumEl.textContent = 'Q' + (stats.total + 1);
}

// ── Erreur API ─────────────────────────────────
function showApiError() {
  document.getElementById('training-main').innerHTML = `
    <div class="card empty-state training-error-card">
      <div class="training-error-icon"><i class="fa-solid fa-face-dizzy"></i></div>
      <h2>API indisponible</h2>
      <p class="training-error-text">Impossible de charger les questions. Vérifiez votre connexion.</p>
      <button onclick="init()" class="btn-primary btn-inline">Réessayer</button>
    </div>`;
}

// ── Init ───────────────────────────────────────
async function init() {
  await loadQuestions();
  await nextQuestion();
}

// Script chargé en bas de page — DOM déjà prêt
if (document.getElementById('training-q-text')) {
  init();
  const btn = document.getElementById('training-confirm');
  if (btn) btn.addEventListener('click', confirmAnswer);
  const btnFou = document.getElementById('btn-t-fou');
  if (btnFou) btnFou.addEventListener('click', useFouT);
  const btnPigeon = document.getElementById('btn-t-pigeon');
  if (btnPigeon) btnPigeon.addEventListener('click', usePigeonT);
  [1,2,3,4].forEach(i => {
    const label = document.getElementById('answer-t-' + i);
    if (label) label.addEventListener('click', function() { selectAnswerT(this, i); });
  });
}
