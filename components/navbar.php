<header class="flex items-center justify-between w-full px-6 py-3 bg-white shadow-md">
  <div class="flex gap-4">
    <a class="flex items-center gap-3" href="<?= BASE_URL ?>home">
      <img src="/public/assets/logo.png" alt="Nutritrack Logo" class="w-8 h-8">
      <h3 class="text-xl font-semibold">Nutritrack</h3>
    </a>

    <div class="relative">
      <form action="<?= BASE_URL ?>search" method="GET">
        <input type="text" name="search" placeholder="Search your food..." class="px-4 py-2 pl-10 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit"><i class="absolute text-gray-500 transform -translate-y-1/2 fas fa-search left-3 top-1/2"></i></button>
      </form>
    </div>
  </div>

  <div class="flex items-center gap-4">
    <nav class="flex items-center gap-4">
      <?php
      $menuItems = [
        ["Camera", "fas fa-camera", "scan"],
        ["Help", "", "help"],
        ["Upgrade", "", "upgrade"],
        ["Comunity", "", "comunity"],
        ["Notification", "fas fa-bell", "notification"],
        ["Chat", "fas fa-comments", "chat"]
      ];
      ?>

      <ul class="flex items-center gap-4">
        <?php foreach ($menuItems as $item) { ?>
          <li>
            <a href="<?= BASE_URL . $item[2] ?>" class="flex items-center gap-2 text-gray-700 hover:text-blue-500">
              <?php if ($item[1] != "") { ?>
                <i class="<?= $item[1] ?> text-lg"></i>
              <?php } else { ?>
                <?= $item[0] ?>
              <?php } ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </nav>

    <?php
    if (isset($_SESSION['user_id'])) {
    ?>
      <a href="<?= BASE_URL ?>profile">
        <div class="flex items-center justify-center w-10 h-10 font-bold text-white bg-gray-300 rounded-full">
          <?= substr($_SESSION['username'], 0, 1) ?>
        </div>
      </a>
    <?php
    } else {
    ?>
      <div>
        <a class="px-4 py-1 bg-white border-2 rounded-xl border-slate-800 text-slate-800" href="<?= BASE_URL ?>login">Sign In</a>
        <a class="px-4 py-1 text-white border-2 bg-slate-800 rounded-xl" href="<?= BASE_URL ?>register">Register</a>
      </div>
    <?php
    }
    ?>
  </div>
</header>