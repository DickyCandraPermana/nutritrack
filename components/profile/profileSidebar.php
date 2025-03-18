<?php include_once 'config/config.php'; ?>

<div class=" bg-emerald-300 w-[20%] h-svh">
  <ul class="flex flex-col w-full h-full">
    <li class="flex flex-row items-center gap-4 px-4 py-5">
      <img src="<?= BASE_URL . $_SESSION['profile_picture'] ?>" class="rounded-full h-14 w-14" alt="">
      <div class="flex flex-col">
        <h3 class="text-lg font-bold"><?= $_SESSION['username'] ?></h3>
        <p class="text-sm"><?= $_SESSION['bio'] ?></p>
      </div>
    </li>
    <a href="<?= BASE_URL ?>profile" class="w-full px-4 py-3 border border-b border-teal-50 bg-emerald-500">
      <li>Dashboard</li>
    </a>
    <a href="<?= BASE_URL ?>profile/update" class="w-full px-4 py-3 border border-b border-teal-50 bg-emerald-500">
      <li>Tambah makanan</li>
    </a>
    <a href="<?= BASE_URL ?>profile/data" class="w-full px-4 py-3 border border-b border-teal-50 bg-emerald-500">
      <li>Lihat makanan</li>
    </a>
  </ul>
</div>