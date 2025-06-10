
<?php require_once 'components/admin/sidebar.php'; ?>

<!-- Main Content -->
<div class="min-h-screen ml-64">
  <!-- Header -->
  <?php require_once 'components/admin/header.php'; ?>

  <!-- Dashboard Content -->
  <main class="p-6">
    <!-- Dashboard Tab -->
    <div id="dashboard-tab" class="tab-content">
      <?php require_once 'components/admin/dashboard.php' ?>
    </div>
  </main>

  <!-- FOOTER SECTION -->
  <?php require_once 'components/admin/footer.php'; ?>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Fungsi animasi counter
    function animateCounter(element, targetValue, duration = 2000) {
      const startValue = 0;
      const increment = targetValue / (duration / 16);
      let currentValue = startValue;

      const timer = setInterval(() => {
        currentValue += increment;
        if (currentValue >= targetValue) {
          currentValue = targetValue;
          clearInterval(timer);
        }
        element.textContent = Math.floor(currentValue).toLocaleString();
      }, 16);
    }

    // Jalankan counter saat tab Dashboard dibuka
    function runDashboardCounters() {
      const adminData = <?= json_encode($data) ?>; // Get data from PHP
      animateCounter(document.getElementById('userCount'), adminData.userCount);
      animateCounter(document.getElementById('foodCount'), adminData.foodCount);
      animateCounter(document.getElementById('premiumCount'), adminData.premiumCount);
    }

    // Simulasi flicker status indikator hanya di dashboard
    function flickerStatusIndicators() {
      const statusIndicators = document.querySelectorAll('#dashboard-tab .w-2.h-2.rounded-full');
      statusIndicators.forEach(indicator => {
        if (Math.random() > 0.95) {
          indicator.style.opacity = '0.5';
          setTimeout(() => {
            indicator.style.opacity = '1';
          }, 200);
        }
      });
    }

    // Call runDashboardCounters on DOMContentLoaded
    runDashboardCounters();
    setInterval(flickerStatusIndicators, 3000);

  });
</script>
