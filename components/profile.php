<div class="flex flex-row">
  <?php
  include 'profile/profileSidebar.php';

  if (isset($_GET['page'])) {
    switch ($_GET['page']) {
      case 'dashboard':
        include 'profile/profileContent.php';
        break;
      case 'update':
        include 'profile/updateProfile.php';
        break;
      case 'tambahMakanan':
        include 'profile/tambahMakanan.php';
        break;
      default:
        include 'profile/profileContent.php';
        break;
    }
  } else {
    include 'profile/lihatMakanan.php';
  } ?>

</div>