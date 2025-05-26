<div class="p-6 bg-gray-100">
  <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-lg">
    <div class="flex items-center gap-3 mb-4">
      <h2 class="text-2xl font-bold">Profil Pengguna</h2>
      <a href="<?= BASE_URL ?>profile/edit" class="flex items-center gap-2 px-4 py-2 text-white transition bg-blue-600 rounded-md hover:bg-blue-700"><i class="fas fa-pencil"></i></a>
    </div>
    <p><strong>Username:</strong> <?= $user["username"] ?></p>
    <p><strong>Nama Lengkap:</strong> <?= $user["first_name"] . " " . $user["last_name"] ?></p>
    <p><strong>Email:</strong> <?= $user["email"] ?></p>
    <p><strong>Bio:</strong> <?= $user["bio"] ?></p>
    <p><strong>Jenis Kelamin:</strong> <?= $user["jenis_kelamin"] == 1 ? "Laki-laki" : "Perempuan" ?></p>
    <p><strong>Nomor Telepon:</strong> <?= $user["phone_number"] ?></p>
    <p><strong>Tanggal Lahir:</strong> <?= $user["tanggal_lahir"] ?> (Usia: <?= $user['umur'] ?> tahun)</p>
    <p><strong>Berat Badan:</strong> <?= $user["berat_badan"] ?> kg</p>
    <p><strong>Tinggi Badan:</strong> <?= $user["tinggi_badan"] ?> cm</p>
    <p><strong>Aktivitas:</strong> <?= $user["aktivitas"] ?></p>
  </div>
  <?php

  if (isset($user['bmi'])) {
  ?>
    <div class="grid max-w-4xl grid-cols-1 gap-4 mx-auto mt-6 md:grid-cols-3">
      <div class="p-6 text-center text-white bg-blue-500 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold">BMI</h3>
        <p class="text-2xl font-semibold"><?= number_format($user["bmi"], 2) ?></p>
      </div>
      <div class="p-6 text-center text-white bg-green-500 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold">BMR</h3>
        <p class="text-2xl font-semibold"><?= number_format($user['bmr'], 2) ?> kcal</p>
      </div>
      <div class="p-6 text-center text-white bg-red-500 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold">TDEE</h3>
        <p class="text-2xl font-semibold"><?= number_format($user['tdee'], 2) ?> kcal</p>
      </div>
    </div>
  <?php
  }

  ?>
</div>