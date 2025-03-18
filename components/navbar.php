<header class="flex flex-row justify-between p-5 border-b-2 border-green-400 bg-green-50">
  <div class="flex flex-row gap-5">
    <a href="<?= BASE_URL ?>" class="border-0">
      <img class="w-8 h-8 bg-[url(../assets/logo.png)] bg-contain border-0">
    </a>
    <nav>
      <ul class="flex flex-row gap-3">
        <a href="#" class="text-md">
          <li>Lens</li>
        </a>
        <a href="#" class="text-md">
          <li>NutriPedia</li>
        </a>
        <a href="#" class="text-md">
          <li>Link</li>
        </a>
        <a href="<?= BASE_URL ?>logout" class="text-md">
          <li>Log out</li>
        </a>
      </ul>
    </nav>
  </div>
  <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { ?>
    <div class="flex flex-row items-center justify-center h-full gap-3">
      <a href="<?= BASE_URL ?>profile">
        <img src="<?= BASE_URL . $_SESSION['profile_picture'] ?>" class="w-10 h-10 rounded-full" alt="">
      </a>
      <div class="flex flex-col">
        <h3 class="text-sm font-medium"><?= $_SESSION['username'] ?></h3>
        <p class="text-xs"><?= $_SESSION['bio'] ?></p>
      </div>
    </div>
  <?php } else { ?>
    <div>
      <a class="px-4 py-1 bg-white border-2 rounded-xl border-slate-800 text-slate-800" href="<?= BASE_URL ?>login">Sign In</a>
      <a class="px-4 py-1 text-white border-2 bg-slate-800 rounded-xl" href="<?= BASE_URL ?>register">Register</a>
    </div>
  <?php } ?>
</header>