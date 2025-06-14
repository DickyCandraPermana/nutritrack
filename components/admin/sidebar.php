<?php
$menuItems = [
  ['icon' => 'chart-pie', 'label' => 'Dashboard', 'tab' => 'dashboard', 'link' => 'admin/dashboard'],
  ['icon' => 'users', 'label' => 'Manajemen User', 'tab' => 'users', 'link' => 'admin/users'],
  ['icon' => 'utensils', 'label' => 'Manajemen Makanan', 'tab' => 'foods',  'link' => 'admin/foods'],
  ['icon' => 'crown', 'label' => 'Fitur Premium', 'tab' => 'premium', 'link' => 'admin/premium'],
  ['icon' => 'sign-out', 'label' => 'Log out', 'tab' => 'logout', 'link' => 'admin/logout'],
];
?>

<div class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 transform bg-white shadow-xl"
  id="sidebar">
  <div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="p-6 gradient-bg">
      <div class="flex items-center space-x-3">
        <div class="p-2 bg-white rounded-xl">
          <img src="<?= BASE_URL ?>public/assets/logo.png" alt="Foto" class="object-cover w-8 h-8 rounded-md" />
        </div>

        <div>
          <h1 class="text-xl font-bold text-white">NutriTrack</h1>
          <p class="text-sm text-emerald-100">Admin Panel</p>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
      <?php foreach ($menuItems as $item): ?>
        <a href="<?= BASE_URL . $item['link'] ?>"
          class="flex items-center px-4 py-3 space-x-3 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 smooth-transition nav-item "
          <?php if (!empty($item['tab'])): ?>
          <?php endif; ?>>
          <i class="w-5 fas fa-<?php echo $item['icon']; ?>"></i>
          <span><?php echo $item['label']; ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Admin Profile -->
    <div class="p-4 border-t border-gray-200">
      <div class="flex items-center space-x-3">
        <?php if (isset($user['profile_picture']) && !empty($user['profile_picture'])): ?>
          <img src="<?= BASE_URL . $user['profile_picture'] ?>" alt="Profile Picture" class="object-cover w-10 h-10 rounded-full">
        <?php else: ?>
          <div class="flex items-center justify-center w-10 h-10 text-lg font-semibold text-white bg-blue-500 rounded-full">
            <?= strtoupper(substr($user['username'] ?? 'A', 0, 1)) ?>
          </div>
        <?php endif; ?>
        <div>
          <p class="text-sm font-medium text-gray-700"><?= $user['username'] ?? 'Admin' ?></p>
          <p class="text-xs text-gray-500"><?= $user['email'] ?? 'admin@example.com' ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
