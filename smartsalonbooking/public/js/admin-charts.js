/**
 * Renders the two Chart.js charts on the admin dashboard.
 * Data is passed in as data-labels / data-values JSON attributes on the
 * <canvas> tags (see resources/views/admin/dashboard.blade.php) so this
 * file has zero knowledge of how the numbers were calculated.
 */
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Chart === 'undefined') return;

  Chart.defaults.font.family = 'Manrope, sans-serif';
  Chart.defaults.color = '#7c6b76';

  renderChart('revenueChart', 'line', {
    borderColor: '#9d1b56',
    backgroundColor: 'rgba(157,27,86,0.12)',
    label: 'Revenue (KSh)',
  });

  renderChart('bookingsChart', 'bar', {
    borderColor: '#c9a24b',
    backgroundColor: 'rgba(201,162,75,0.55)',
    label: 'Bookings',
  });

  function renderChart(canvasId, type, style) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return;
    var labels = JSON.parse(canvas.dataset.labels || '[]');
    var values = JSON.parse(canvas.dataset.values || '[]');

    new Chart(canvas.getContext('2d'), {
      type: type,
      data: {
        labels: labels,
        datasets: [{
          label: style.label,
          data: values,
          borderColor: style.borderColor,
          backgroundColor: style.backgroundColor,
          borderRadius: 8,
          tension: 0.35,
          fill: true,
          pointRadius: 3,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
          x: { grid: { display: false } },
        },
      },
    });
  }
});
