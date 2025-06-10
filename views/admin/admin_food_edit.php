<?php require_once 'components/admin/sidebar.php'; ?>

<!-- Main Content -->
<div class="min-h-screen ml-64">
  <!-- Header -->
  <?php require_once 'components/admin/header.php'; ?>

  <!-- Dashboard Content -->
  <main class="p-6">
    <div id="update-food-tab" class="tab-content">
      <?php require_once 'components/admin/food/food_update.php'; ?>
    </div>
  </main>

  <!-- FOOTER SECTION -->
  <?php require_once 'components/admin/footer.php'; ?>
</div>