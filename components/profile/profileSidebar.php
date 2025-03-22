<?php include_once 'config/config.php'; ?>

<!-- Sidebar -->
<div class="fixed z-50 w-64 h-screen text-white transition-all duration-300 ease-in-out transform -translate-x-full bg-gray-900 lg:static lg:z-0 lg:translate-x-0" id="sidebar">
  <ul class="flex flex-col w-full h-full pt-5">
    <?php
    $menuItems = [
      ["Dashboard", "fas fa-home", "dashboard"],
      ["Personal", "fas fa-user", "personal"],
      ["Tracking Gizi", "fas fa-chart-line", "tracking"],
      ["Schedules", "fas fa-calendar", "schedules"],
      ["Meal Plans", "fas fa-utensils", "meal-plans"],
      ["Premium", "fas fa-check-circle", "premium"],
      ["Pemindai Makanan", "fas fa-camera", "scanner"],
      ["Messages", "fas fa-comments", "messages"],
      ["Log out", "fas fa-sign-out", "logout"]
    ];
    foreach ($menuItems as $item) {
      $active = (strpos($_SERVER['REQUEST_URI'], $item[2]) !== false) ? "bg-emerald-600 border-l-4 border-yellow-400" : "hover:bg-emerald-700";
      echo "<a href='" . BASE_URL . "profile/" . $item[2] . "' class='sidebar-item flex items-center px-6 py-3 gap-3 $active transition'>
                    <i class='$item[1]'></i>
                    <span>$item[0]</span>
                </a>";
    }
    ?>
  </ul>
</div>

<!-- Toggle Button (Mobile) -->
<button id="sidebar-toggle" class="fixed z-50 p-2 text-white bg-gray-800 rounded-md lg:hidden top-5 left-5">
  <i class="fas fa-bars"></i>
</button>

<!-- JS for Sidebar Toggle -->
<script>
  document.getElementById('sidebar-toggle').addEventListener('click', function() {
    let sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('-translate-x-full');
  });
</script>