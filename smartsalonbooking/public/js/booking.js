/**
 * Smart Salon – multi-step booking form (v2)
 * Horizontal slide transitions, time-of-day slot grouping, receipt summary.
 */
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('booking-form');
  if (!form) return;

  var totalSteps = 5;
  var current = 1;
  var state = {
    service_id: null, employee_id: null, date: null, start_time: null,
    duration: null, price: null, serviceName: null, employeeName: null
  };

  var panels     = form.querySelectorAll('[data-step]');
  var indicators = document.querySelectorAll('[data-step-indicator]');
  var slotsUrl   = form.dataset.slotsUrl;

  // ── Initial render ──────────────────────────────────────────────────────────
  goToStep(1);

  // ── Step 1 – service ────────────────────────────────────────────────────────
  form.querySelectorAll('input[name="service_id"]').forEach(function (input) {
    input.addEventListener('change', function () {
      state.service_id  = input.value;
      state.duration    = input.dataset.duration;
      state.price       = input.dataset.price;
      state.serviceName = input.dataset.name;
      highlightCard(input);
      filterEmployeesForService(input.value);
      setNextEnabled(1, true);
    });
  });

  // ── Step 2 – specialist ─────────────────────────────────────────────────────
  form.querySelectorAll('input[name="employee_id"]').forEach(function (input) {
    input.addEventListener('change', function () {
      state.employee_id   = input.value;
      state.employeeName  = input.dataset.name;
      highlightCard(input);
      setNextEnabled(2, true);
      fetchSlotsIfReady();
    });
  });

  // ── Step 3 – date ───────────────────────────────────────────────────────────
  var dateInput = document.getElementById('appointment_date');
  if (dateInput) {
    state.date = dateInput.value || null;
    dateInput.addEventListener('change', function () {
      state.date       = dateInput.value;
      state.start_time = null;
      document.getElementById('start_time_input').value = '';
      setNextEnabled(3, false);
      fetchSlotsIfReady();
    });
  }

  // ── Navigation buttons ──────────────────────────────────────────────────────
  form.querySelectorAll('[data-next-step]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (current === 4 && !validateStep4()) return;
      if (current === 4) buildSummary();
      goToStep(current + 1);
    });
  });
  form.querySelectorAll('[data-prev-step]').forEach(function (btn) {
    btn.addEventListener('click', function () { goToStep(current - 1); });
  });

  // ── Core step transition ────────────────────────────────────────────────────
  function goToStep(step) {
    if (step < 1 || step > totalSteps) return;
    var prev = current;
    current  = step;

    panels.forEach(function (panel) {
      var n = parseInt(panel.dataset.step, 10);
      panel.classList.remove('is-active', 'is-before', 'is-after');
      if (n === step) {
        panel.classList.add('is-active');
      } else if (n < step) {
        panel.classList.add('is-before');
      } else {
        panel.classList.add('is-after');
      }
    });

    // Stepper pills
    indicators.forEach(function (ind) {
      var n = parseInt(ind.dataset.stepIndicator, 10);
      ind.classList.remove('is-active', 'is-done');
      if (n === step)    ind.classList.add('is-active');
      if (n < step)      ind.classList.add('is-done');
    });

    // Animated progress bar
    var progress = document.getElementById('tracker-progress');
    if (progress) {
      var pct = ((step - 1) / (totalSteps - 1)) * 100;
      progress.style.width = pct + '%';
    }

    window.scrollTo({ top: (form.offsetTop || 0) - 120, behavior: 'smooth' });
  }

  // ── Card highlight ──────────────────────────────────────────────────────────
  function highlightCard(input) {
    var grid = input.closest('.option-grid');
    if (!grid) return;
    grid.querySelectorAll('.option-card').forEach(function (c) { c.classList.remove('is-selected'); });
    input.closest('.option-card').classList.add('is-selected');
  }

  // ── Filter employees ────────────────────────────────────────────────────────
  function filterEmployeesForService(serviceId) {
    form.querySelectorAll('#employee-options .option-card').forEach(function (card) {
      var ids = (card.dataset.services || '').split(',').map(function (s) { return s.trim(); });
      var visible = ids.indexOf(String(serviceId)) !== -1;
      card.style.display = visible ? '' : 'none';
      var radio = card.querySelector('input');
      if (!visible && radio && radio.checked) {
        radio.checked = false;
        card.classList.remove('is-selected');
        state.employee_id = null;
        setNextEnabled(2, false);
      }
    });
  }

  // ── Fetch slots ─────────────────────────────────────────────────────────────
  function fetchSlotsIfReady() {
    var grid = document.getElementById('slot-grid');
    if (!grid) return;
    if (!state.employee_id || !state.service_id || !state.date) {
      grid.innerHTML = emptySlotHtml('Choose a service, a specialist and a date to see open times.');
      return;
    }
    grid.innerHTML = '<div class="slot-loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading times…</div>';

    var url = slotsUrl +
      '?employee_id=' + encodeURIComponent(state.employee_id) +
      '&service_id='  + encodeURIComponent(state.service_id)  +
      '&date='         + encodeURIComponent(state.date);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (res) { return res.json(); })
      .then(function (data) { renderSlots(grid, data.slots || []); })
      .catch(function () {
        grid.innerHTML = '<p class="form-error"><i class="fa-solid fa-triangle-exclamation"></i> Could not load times. Please try a different date.</p>';
      });
  }

  function emptySlotHtml(msg) {
    return '<div class="slot-empty-state"><i class="fa-regular fa-calendar-xmark"></i><p>' + msg + '</p></div>';
  }

  // ── Render slots grouped by time of day ─────────────────────────────────────
  function renderSlots(grid, slots) {
    if (!slots.length) {
      grid.innerHTML = emptySlotHtml('No open slots that day — please pick another date.');
      return;
    }

    var morning   = [], afternoon = [], evening = [];
    slots.forEach(function (slot) {
      var hour = parseInt((slot.value || '00:00').split(':')[0], 10);
      if (hour < 12)      morning.push(slot);
      else if (hour < 17) afternoon.push(slot);
      else                evening.push(slot);
    });

    grid.innerHTML = '';

    function renderGroup(label, icon, group) {
      if (!group.length) return;
      var heading = document.createElement('div');
      heading.className = 'slot-group-heading';
      heading.innerHTML = '<i class="fa-solid fa-' + icon + '"></i> ' + label;
      grid.appendChild(heading);

      var row = document.createElement('div');
      row.className = 'slot-row';
      group.forEach(function (slot) {
        var btn = document.createElement('button');
        btn.type      = 'button';
        btn.className = 'slot';
        btn.textContent = slot.label;
        if (slot.taken) btn.classList.add('is-taken'), btn.disabled = true;
        btn.addEventListener('click', function () {
          grid.querySelectorAll('.slot').forEach(function (s) { s.classList.remove('is-selected'); });
          btn.classList.add('is-selected');
          state.start_time = slot.value;
          document.getElementById('start_time_input').value = slot.value;
          setNextEnabled(3, true);
        });
        row.appendChild(btn);
      });
      grid.appendChild(row);
    }

    renderGroup('Morning',   'cloud-sun', morning);
    renderGroup('Afternoon', 'sun',       afternoon);
    renderGroup('Evening',   'moon',      evening);
  }

  // ── Step 4 validation ───────────────────────────────────────────────────────
  function validateStep4() {
    var fields = form.querySelectorAll('[data-step="4"] [required]');
    for (var i = 0; i < fields.length; i++) {
      if (!fields[i].value.trim()) {
        fields[i].reportValidity();
        return false;
      }
    }
    return true;
  }

  // ── Build receipt summary ───────────────────────────────────────────────────
  function buildSummary() {
    var box = document.getElementById('booking-summary-box');
    if (!box) return;

    var endTime     = addMinutes(state.start_time, parseInt(state.duration || 0, 10));
    var formattedDate = formatDateString(state.date);
    var price       = parseFloat(state.price || 0).toFixed(2);

    box.innerHTML =
      '<div class="receipt-card">' +
        '<div class="receipt-header">' +
          '<span class="receipt-icon"><i class="fa-solid fa-receipt"></i></span>' +
          '<div>' +
            '<div class="receipt-salon-name">Smart Salon</div>' +
            '<div class="text-muted" style="font-size:.82rem;">Booking Summary</div>' +
          '</div>' +
        '</div>' +
        '<div class="receipt-divider"></div>' +
        '<div class="receipt-body">' +
          receiptRow('<i class="fa-solid fa-scissors"></i> Service',    escapeHtml(state.serviceName  || '—')) +
          receiptRow('<i class="fa-solid fa-user-tie"></i> Specialist', escapeHtml(state.employeeName || '—')) +
          receiptRow('<i class="fa-regular fa-calendar"></i> Date',     escapeHtml(formattedDate       || '—')) +
          receiptRow('<i class="fa-regular fa-clock"></i> Time',        escapeHtml(formatTime(state.start_time)) + ' – ' + escapeHtml(formatTime(endTime))) +
          receiptRow('<i class="fa-regular fa-hourglass-half"></i> Duration', escapeHtml(state.duration || '0') + ' min') +
        '</div>' +
        '<div class="receipt-divider"></div>' +
        '<div class="receipt-footer">' +
          '<span>Total Due</span>' +
          '<span class="receipt-total">$' + price + '</span>' +
        '</div>' +
      '</div>';
  }

  function receiptRow(label, value) {
    return '<div class="receipt-row"><span>' + label + '</span><strong>' + value + '</strong></div>';
  }

  // ── Helpers ─────────────────────────────────────────────────────────────────
  function setNextEnabled(step, enabled) {
    var btn = form.querySelector('[data-step="' + step + '"] [data-next-step]');
    if (btn) btn.disabled = !enabled;
  }

  function addMinutes(hhmm, minutes) {
    if (!hhmm) return '';
    var parts = hhmm.split(':');
    var d = new Date(2000, 0, 1, parseInt(parts[0], 10), parseInt(parts[1], 10));
    d.setMinutes(d.getMinutes() + minutes);
    return String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
  }

  function formatTime(hhmm) {
    if (!hhmm) return '';
    var parts = hhmm.split(':');
    var h = parseInt(parts[0], 10), m = parseInt(parts[1], 10);
    var ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return h + ':' + String(m).padStart(2, '0') + ' ' + ampm;
  }

  function formatDateString(dateStr) {
    if (!dateStr) return '';
    var d    = new Date(dateStr + 'T00:00:00');
    var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    var mons = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return days[d.getDay()] + ', ' + mons[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
  }

  function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }
});
