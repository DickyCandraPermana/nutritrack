<div class="p-6 bg-white rounded-lg shadow-md welcome-section">
  <div>
    <h4 class="text-xl font-semibold">Welcome back, <?= $user['username'] ?></h4>
    <p class="text-sm text-gray-500"><?= date('l, j F Y') ?></p>

    

    <?php include 'components/profile/charts.php' ?>