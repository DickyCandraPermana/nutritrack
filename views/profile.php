<?php
include 'components/errorHandling.php';
?>

<div class="flex flex-row">
  <?php
  include 'components/profile/profileSidebar.php';
  ?>
  <div class="w-full">
    <?php
    include 'components/navbar.php';
    include 'components/profile/profileContent.php'
    ?>
  </div>
</div>