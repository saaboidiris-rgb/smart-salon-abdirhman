/**
 * Smart Salon - global vanilla JS
 * Handles: mobile nav toggle, button ripple effect, toast notifications,
 * a reusable confirm dialog (replaces window.confirm with something pretty),
 * auto-dismissing alert banners, and scroll-reveal animations.
 * No build step, no dependencies - just plain browser JS.
 */
document.addEventListener('DOMContentLoaded', function () {
  initMobileMenu();
  initRipple();
  initScrollReveal();
  initAutoDismissAlerts();
  initConfirmDialogs();
  window.SmartSalon = { toast: showToast, confirmAction: confirmAction };
});

/* ---------------- Mobile menu ---------------- */
function initMobileMenu() {
  var toggle = document.querySelector('[data-mobile-toggle]');
  var menu = document.querySelector('[data-mobile-menu]');
  if (!toggle || !menu) return;
  toggle.addEventListener('click', function () {
    menu.classList.toggle('is-open');
    toggle.textContent = menu.classList.contains('is-open') ? '✕' : '☰';
  });
}

/* ---------------- Button ripple effect ---------------- */
function initRipple() {
  document.querySelectorAll('.btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var rect = btn.getBoundingClientRect();
      var ripple = document.createElement('span');
      var size = Math.max(rect.width, rect.height);
      ripple.className = 'ripple';
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
      ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
      btn.appendChild(ripple);
      setTimeout(function () { ripple.remove(); }, 650);
    });
  });
}

/* ---------------- Scroll reveal ---------------- */
function initScrollReveal() {
  var items = document.querySelectorAll('.animate-on-scroll');
  if (!items.length) return;
  if (!('IntersectionObserver' in window)) {
    items.forEach(function (el) { el.classList.add('is-visible'); });
    return;
  }
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
  items.forEach(function (el) { observer.observe(el); });
}

/* ---------------- Auto-dismiss alert banners ---------------- */
function initAutoDismissAlerts() {
  document.querySelectorAll('[data-auto-dismiss]').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .4s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 400);
    }, 4500);
  });

  // Flash messages rendered by the server (session('success') / session('error'))
  // are also shown as toasts for consistency across the app.
  document.querySelectorAll('[data-flash]').forEach(function (el) {
    var type = el.getAttribute('data-flash');
    var message = el.textContent.trim();
    if (message) showToast(message, type === 'error' ? 'error' : type);
  });
}

/* ---------------- Toasts ---------------- */
function showToast(message, type) {
  type = type || 'info';
  var container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }
  var toast = document.createElement('div');
  toast.className = 'toast toast--' + type;
  var icon = type === 'success' ? '<i class="fa-solid fa-circle-check text-success"></i>' : type === 'error' ? '<i class="fa-solid fa-circle-xmark text-danger"></i>' : '<i class="fa-solid fa-circle-info text-info"></i>';
  toast.innerHTML = '<span style="display:flex;align-items:center;">' + icon + '</span><span>' + message + '</span>';
  container.appendChild(toast);
  setTimeout(function () {
    toast.style.transition = 'opacity .3s ease, transform .3s ease';
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(20px)';
    setTimeout(function () { toast.remove(); }, 300);
  }, 4000);
}

/* ---------------- Confirm dialog (replaces window.confirm) ----------------
 * Usage in Blade:
 * <form data-confirm-submit
 *       data-confirm-title="Delete this service?"
 *       data-confirm-message="This can't be undone."
 *       data-confirm-label="Delete" action="..." method="POST"> ... </form>
 */
function initConfirmDialogs() {
  var overlay = document.createElement('div');
  overlay.className = 'modal-overlay';
  overlay.innerHTML =
    '<div class="modal-box">' +
    '  <h3 data-modal-title>Are you sure?</h3>' +
    '  <p data-modal-message class="mb-0">This action cannot be undone.</p>' +
    '  <div class="modal-actions">' +
    '    <button type="button" class="btn btn--ghost" data-modal-cancel>Cancel</button>' +
    '    <button type="button" class="btn btn--danger" data-modal-confirm>Confirm</button>' +
    '  </div>' +
    '</div>';
  document.body.appendChild(overlay);

  var pendingForm = null;

  document.querySelectorAll('[data-confirm-submit]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (form.dataset.confirmed === 'true') return; // already approved, let it go
      e.preventDefault();
      pendingForm = form;
      overlay.querySelector('[data-modal-title]').textContent = form.dataset.confirmTitle || 'Are you sure?';
      overlay.querySelector('[data-modal-message]').textContent = form.dataset.confirmMessage || 'This action cannot be undone.';
      overlay.querySelector('[data-modal-confirm]').textContent = form.dataset.confirmLabel || 'Confirm';
      overlay.classList.add('is-open');
    });
  });

  overlay.querySelector('[data-modal-cancel]').addEventListener('click', function () {
    overlay.classList.remove('is-open');
    pendingForm = null;
  });
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) { overlay.classList.remove('is-open'); pendingForm = null; }
  });
  overlay.querySelector('[data-modal-confirm]').addEventListener('click', function () {
    overlay.classList.remove('is-open');
    if (pendingForm) {
      pendingForm.dataset.confirmed = 'true';
      pendingForm.submit();
    }
  });
}

/**
 * Programmatic version, e.g. before an AJAX call:
 * SmartSalon.confirmAction('Cancel booking?', 'This slot will be released.').then(ok => { if (ok) ... })
 */
function confirmAction(title, message) {
  return new Promise(function (resolve) {
    var overlay = document.createElement('div');
    overlay.className = 'modal-overlay is-open';
    overlay.innerHTML =
      '<div class="modal-box">' +
      '  <h3>' + title + '</h3>' +
      '  <p class="mb-0">' + (message || '') + '</p>' +
      '  <div class="modal-actions">' +
      '    <button type="button" class="btn btn--ghost" data-cancel>Cancel</button>' +
      '    <button type="button" class="btn btn--danger" data-ok>Yes, continue</button>' +
      '  </div>' +
      '</div>';
    document.body.appendChild(overlay);
    overlay.querySelector('[data-cancel]').onclick = function () { overlay.remove(); resolve(false); };
    overlay.querySelector('[data-ok]').onclick = function () { overlay.remove(); resolve(true); };
  });
}
