// CIMAGE Exam System - Main JS

// Page Loader
window.addEventListener('load', () => {
  const loader = document.getElementById('pageLoader');
  if (loader) setTimeout(() => { loader.style.opacity = '0'; setTimeout(() => loader.remove(), 500); }, 1200);
});

// Sidebar Toggle
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) sidebar.classList.toggle('open');
}
document.addEventListener('click', e => {
  const sidebar = document.getElementById('sidebar');
  const toggle = document.querySelector('.menu-toggle');
  if (sidebar && !sidebar.contains(e.target) && toggle && !toggle.contains(e.target)) {
    sidebar.classList.remove('open');
  }
});

// Toast Notifications
function showToast(msg, type = 'info', duration = 3500) {
  let wrap = document.querySelector('.toast-wrap');
  if (!wrap) { wrap = document.createElement('div'); wrap.className = 'toast-wrap'; document.body.appendChild(wrap); }
  const icons = { success: '✓', error: '✕', info: 'ℹ', warning: '⚠' };
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  t.innerHTML = `<span style="font-size:16px">${icons[type]||'ℹ'}</span><span>${msg}</span>`;
  wrap.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateX(100px)'; t.style.transition = 'all .3s'; setTimeout(() => t.remove(), 300); }, duration);
}

// Delete Confirm
function confirmDelete(url, msg) {
  msg = msg || 'Are you sure you want to delete this item?';
  if (confirm(msg)) window.location.href = url;
}

// Search Table
function searchTable(inputId, tableId) {
  const input = document.getElementById(inputId);
  const table = document.getElementById(tableId);
  if (!input || !table) return;
  input.addEventListener('keyup', function() {
    const val = this.value.toLowerCase();
    Array.from(table.tBodies[0].rows).forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
  });
}

// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(a => {
  setTimeout(() => { a.style.opacity = '0'; a.style.transition = 'opacity .4s'; setTimeout(() => a.remove(), 400); }, 4000);
});

// Topbar search toggle
const searchToggle = document.getElementById('searchToggle');
if (searchToggle) searchToggle.addEventListener('click', () => {
  document.getElementById('searchBar').classList.toggle('active');
});
