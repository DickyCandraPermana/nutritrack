<?php
include 'components/errorHandling.php';
?>

<div class="flex flex-row">
  <?php
  include 'components/profile/profileSidebar.php';
  ?>
  <div class="flex flex-col w-full">
    <?php
    include 'components/navbar.php';
    //* include 'components/profile/foodTracking.php';
    include 'components/profile/lihatMakanan.php';
    ?>
  </div>
</div>