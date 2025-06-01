<?php require_once 'components/errorHandling.php'; ?>
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

    <!-- Users Tab -->
    <div id="users-tab" class="tab-content">
      <?php require_once 'components/admin/user/user_input.php'; ?>
    </div>


    <div id="foods-tab" class="tab-content">Selamat datang di Manajemen Makanan!</div>

    <div id="premium-tab" class="tab-content">Selamat datang di Fitur Premium!</div>

    <div id="analytics-tab" class="tab-content">Selamat datang di Analytics!</div>
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
      animateCounter(document.getElementById('userCount'), 1247);
      animateCounter(document.getElementById('foodCount'), 856);
      animateCounter(document.getElementById('premiumCount'), 189);
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

    setInterval(flickerStatusIndicators, 3000);

    // Fungsi tab switch
    window.showTab = function(tabId) {
      const allTabs = document.querySelectorAll('.tab-content');
      allTabs.forEach(tab => tab.classList.add('hidden'));

      const targetTab = document.getElementById(`${tabId}-tab`);
      if (targetTab) {
        targetTab.classList.remove('hidden');
      }

      const titles = {
        dashboard: 'Dashboard Admin',
        users: 'Manajemen User',
        foods: 'Manajemen Makanan',
        premium: 'Fitur Premium',
        analytics: 'Analytics'
      };

      const subtitles = {
        dashboard: 'Kelola sistem NutriTrack dengan mudah',
        users: 'Kelola data pengguna dan akses',
        foods: 'Atur data makanan dan kandungan',
        premium: 'Kelola fitur dan akses premium',
        analytics: 'Analisa performa dan data pengguna'
      };

      document.getElementById('pageTitle').innerText = titles[tabId] || '';
      document.getElementById('pageSubtitle').innerText = subtitles[tabId] || '';

      if (tabId === 'dashboard') {
        runDashboardCounters();
      }
    };

    // Secara default tampilkan dashboard
    showTab('users');

  });
</script>