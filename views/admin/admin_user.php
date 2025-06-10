<?php require_once 'components/admin/sidebar.php'; ?>

<!-- Main Content -->
<div class="min-h-screen ml-64">
  <!-- Header -->
  <?php require_once 'components/admin/header.php'; ?>

  <!-- Dashboard Content -->
  <main class="p-6">

    <!-- Users Tab -->
    <div id="users-tab" class="tab-content">
      <?php require_once 'components/admin/user/user_table.php'; ?>
    </div>

  </main>

  <!-- FOOTER SECTION -->
  <?php require_once 'components/admin/footer.php'; ?>
</div>