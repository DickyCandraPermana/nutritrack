<div class="p-6 bg-gray-100">
  <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-lg">
    <div class="flex items-center gap-3 mb-4">
      <h2 class="text-2xl font-bold">Profil Pengguna</h2>
      <a href="<?= BASE_URL ?>profile/edit" class="flex items-center gap-2 px-4 py-2 text-white transition bg-blue-600 rounded-md hover:bg-blue-700"><i class="fas fa-pencil"></i></a>
    </div>
    <p><strong>Username:</strong> <?= $_SESSION["username"] ?></p>
    <p><strong>Nama Lengkap:</strong> <?= $_SESSION["first_name"] . " " . $_SESSION["last_name"] ?></p>
    <p><strong>Email:</strong> <?= $_SESSION["email"] ?></p>
    <p><strong>Bio:</strong> <?= $_SESSION["bio"] ?></p>
    <p><strong>Jenis Kelamin:</strong> <?= $_SESSION["jenis_kelamin"] ?></p>
    <p><strong>Nomor Telepon:</strong> <?= $_SESSION["phone_number"] ?></p>
    <p><strong>Tanggal Lahir:</strong> <?= $_SESSION["tanggal_lahir"] ?> (Usia: <?= $_SESSION['umur'] ?> tahun)</p>
    <p><strong>Berat Badan:</strong> <?= $_SESSION["berat_badan"] ?> kg</p>
    <p><strong>Tinggi Badan:</strong> <?= $_SESSION["tinggi_badan"] ?> cm</p>
    <p><strong>Aktivitas:</strong> <?= $_SESSION["aktivitas"] ?></p>
  </div>
  <?php

  if (isset($_SESSION['bmi'])) {
  ?>
    <div class="grid max-w-4xl grid-cols-1 gap-4 mx-auto mt-6 md:grid-cols-3">
      <div class="p-6 text-center text-white bg-blue-500 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold">BMI</h3>
        <p class="text-2xl font-semibold"><?= number_format($_SESSION["bmi"], 2) ?></p>
      </div>
      <div class="p-6 text-center text-white bg-green-500 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold">BMR</h3>
        <p class="text-2xl font-semibold"><?= number_format($_SESSION['bmr'], 2) ?> kcal</p>
      </div>
      <div class="p-6 text-center text-white bg-red-500 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold">TDEE</h3>
        <p class="text-2xl font-semibold"><?= number_format($_SESSION['tdee'], 2) ?> kcal</p>
      </div>
    </div>
  <?php
  }

  ?>
</div>