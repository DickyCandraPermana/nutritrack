<?php require_once 'components/errorHandling.php'; ?>
<?php require_once 'components/admin/sidebar.php'; ?>

<!-- Main Content -->
<div class="min-h-screen ml-64">
  <!-- Header -->
  <?php require_once 'components/admin/header.php'; ?>

  <!-- Dashboard Content -->
  <main class="p-6">
    <!-- Dashboard Tab -->
    <div id="dashboard-tab" class="hidden tab-content">
      <?php require_once 'components/admin/dashboard.php' ?>
    </div>

    <!-- Users Tab -->
    <div id="users-tab" class="hidden tab-content">
      <?php require_once 'components/admin/user/user_table.php'; ?>
    </div>

    <div id="tambah-user-tab" class="hidden tab-content">
      <?php require_once 'components/admin/user/user_input.php'; ?>
    </div>

    <div id="update-user-tab" class="hidden tab-content">
      <?php require_once 'components/admin/user/user_update.php'; ?>
    </div>

    <div id="foods-tab" class="hidden tab-content">
      <?php require_once 'components/admin/food/food_table.php'; ?>
    </div>

    <div id="tambah-food-tab" class="hidden tab-content">
      <?php require_once 'components/admin/food/food_input.php'; ?>
    </div>

    <div id="update-food-tab" class="hidden tab-content">
      <?php require_once 'components/admin/food/food_update.php'; ?>
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
    window.showTab = function() {
      const url = new URL(window.location.href);
      const tabId = url.pathname.split('/').pop();
      const allTabs = document.querySelectorAll('.tab-content');
      if (tabId === 'logout') {
        window.location.href = '<?= BASE_URL ?>logout';
      }
      console.log(tabId);
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
        analytics: 'Analytics',
        "tambah-user": 'Tambah User Baru',
        "update-user": "Edit Data User"
      };

      const subtitles = {
        dashboard: 'Kelola sistem NutriTrack dengan mudah',
        users: 'Kelola data pengguna dan akses',
        foods: 'Atur data makanan dan kandungan',
        premium: 'Kelola fitur dan akses premium',
        analytics: 'Analisa performa dan data pengguna',
        "tambah-user": 'Tambah user baru ke database',
        "update-user": "Edit data user"
      };

      document.getElementById('pageTitle').innerText = titles[tabId] || '';
      document.getElementById('pageSubtitle').innerText = subtitles[tabId] || '';

      if (tabId === 'dashboard') {
        runDashboardCounters();
      }
    };

    showTab();
  });
</script>