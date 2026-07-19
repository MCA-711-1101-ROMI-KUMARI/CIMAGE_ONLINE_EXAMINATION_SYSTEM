// CIMAGE - Online Exam Interface JS

let examDuration = 60; // minutes - will be overridden
let timeLeft = examDuration * 60;
let timerInterval;
let currentQ = 1;
let totalQ = 10;
let answers = {}; // {qNum: 'A'/'B'/'C'/'D'}
let marked = {}; // {qNum: true}

function initExam(duration, total) {
  examDuration = duration;
  totalQ = total;
  timeLeft = duration * 60;
  startTimer();
  goToQuestion(1);
  renderPalette();
}

function startTimer() {
  updateTimerDisplay();
  timerInterval = setInterval(() => {
    timeLeft--;
    updateTimerDisplay();
    if (timeLeft <= 0) { clearInterval(timerInterval); autoSubmit(); }
  }, 1000);
}

function updateTimerDisplay() {
  const h = Math.floor(timeLeft / 3600);
  const m = Math.floor((timeLeft % 3600) / 60);
  const s = timeLeft % 60;
  const str = h > 0 ? `${pad(h)}:${pad(m)}:${pad(s)}` : `${pad(m)}:${pad(s)}`;
  const el = document.getElementById('timerDisplay');
  const box = document.getElementById('timerBox');
  if (el) el.textContent = str;
  if (box) {
    box.className = 'timer-box';
    if (timeLeft <= 300) box.classList.add('danger');
    else if (timeLeft <= 600) box.classList.add('warn');
  }
}

function pad(n) { return n < 10 ? '0' + n : n; }

function selectAnswer(qNum, option) {
  answers[qNum] = option;
  document.querySelectorAll('.option-item').forEach(el => el.classList.remove('selected'));
  const sel = document.querySelector(`.option-item[data-opt="${option}"]`);
  if (sel) { sel.classList.add('selected'); sel.querySelector('.option-badge').style.background = '#1a3c8f'; sel.querySelector('.option-badge').style.color = '#fff'; }
  updatePalette();
  // Update hidden input
  const inp = document.getElementById('answers_json');
  if (inp) inp.value = JSON.stringify(answers);
}

function goToQuestion(num) {
  if (num < 1 || num > totalQ) return;
  // Save current displayed question state
  currentQ = num;
  // Show the correct question block
  document.querySelectorAll('.question-block').forEach(b => b.style.display = 'none');
  const qBlock = document.getElementById(`q_block_${num}`);
  if (qBlock) qBlock.style.display = 'block';
  // Update display
  document.querySelectorAll('.option-item').forEach(el => {
    el.classList.remove('selected');
    el.querySelector('.option-badge').style.background = '';
    el.querySelector('.option-badge').style.color = '';
  });
  if (answers[num]) {
    const sel = document.querySelector(`#q_block_${num} .option-item[data-opt="${answers[num]}"]`);
    if (sel) { sel.classList.add('selected'); }
  }
  updatePalette();
  document.getElementById('prevBtn').disabled = num === 1;
  document.getElementById('nextBtn').textContent = num === totalQ ? 'Submit' : 'Next ›';
}

function nextQuestion() {
  if (currentQ === totalQ) { submitExam(); return; }
  goToQuestion(currentQ + 1);
}

function prevQuestion() { goToQuestion(currentQ - 1); }

function markForReview() {
  marked[currentQ] = !marked[currentQ];
  updatePalette();
}

function renderPalette() {
  const grid = document.getElementById('paletteGrid');
  if (!grid) return;
  grid.innerHTML = '';
  for (let i = 1; i <= totalQ; i++) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'pal-btn';
    btn.textContent = i;
    btn.onclick = () => goToQuestion(i);
    btn.id = `pal_${i}`;
    grid.appendChild(btn);
  }
  updatePalette();
}

function updatePalette() {
  for (let i = 1; i <= totalQ; i++) {
    const btn = document.getElementById(`pal_${i}`);
    if (!btn) continue;
    btn.className = 'pal-btn';
    if (i === currentQ) btn.classList.add('current');
    else if (marked[i]) btn.classList.add('marked');
    else if (answers[i]) btn.classList.add('answered');
  }
}

function autoSubmit() {
  alert('Time is up! Your exam is being submitted automatically.');
  document.getElementById('examForm').submit();
}

function submitExam() {
  const answered = Object.keys(answers).length;
  const unanswered = totalQ - answered;
  let msg = `You have answered ${answered} out of ${totalQ} questions.`;
  if (unanswered > 0) msg += `\n${unanswered} question(s) are unanswered.`;
  msg += '\n\nAre you sure you want to submit?';
  if (confirm(msg)) {
    clearInterval(timerInterval);
    const inp = document.getElementById('answers_json');
    if (inp) inp.value = JSON.stringify(answers);
    document.getElementById('examForm').submit();
  }
}
