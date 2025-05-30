<div class="p-5 bg-gray-100">
  <div class="max-w-5xl p-5 mx-auto bg-white rounded-lg shadow">
    <div class="flex justify-between mb-4">
      <h1 class="text-2xl font-bold">Riwayat Makanan <?= $_SESSION['username'] ?></h1>
      <a href="<?= BASE_URL ?>profile/tambah-makanan" class="flex items-center gap-2 px-4 py-2 text-white transition bg-blue-600 rounded-md hover:bg-blue-700"><i class="fas fa-plus"></i></a>
    </div>

    <table class="min-w-full overflow-hidden bg-white border border-gray-300 rounded-lg">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 border">Nama Makanan</th>
          <th class="px-4 py-2 border">Deskripsi</th>
          <th class="px-4 py-2 border">Tanggal</th>
          <th class="px-4 py-2 border">Waktu Makan</th>
          <th class="px-4 py-2 border">Porsi</th>
          <th class="px-4 py-2 border">Kalori</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (count($foodData) > 0) {
          $data = $foodData;

          // LOOPING DATA
          foreach ($data as $makanan) {
            echo "<tr class='border-t'>";
            echo "<td class='px-4 py-2 border'>{$makanan['nama_makanan']}</td>";
            echo "<td class='px-4 py-2 border'>{$makanan['deskripsi']}</td>";
            echo "<td class='px-4 py-2 border'>{$makanan['tanggal']}</td>";
            echo "<td class='px-4 py-2 border'>{$makanan['waktu_makan']}</td>";
            echo "<td class='px-4 py-2 border'>{$makanan['jumlah_porsi']}</td>";
            echo "<td class='px-4 py-2 border'>";
            echo "<ul class='pl-5'>";
            echo "<li>" . ($makanan['nutrisi'][0][1] * $makanan['jumlah_porsi'])  . " {$makanan['nutrisi'][0][2]}</li>";
            // foreach ($makanan['nutrisi'] as $nutrisi) {
            //   echo "<li>" . $nutrisi[1] . " " . $nutrisi[2] . " - " . $nutrisi[0] . "</li>";
            // }
            echo "</ul>";
            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='6' class='px-4 py-2 text-center'>Tidak ada data makanan</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>