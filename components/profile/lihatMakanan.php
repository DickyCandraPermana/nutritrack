<div class="p-5 bg-gray-100">
  <div class="max-w-5xl p-5 mx-auto bg-white rounded-lg shadow">
    <h1 class="mb-4 text-2xl font-bold">Riwayat Makanan <?= $_SESSION['username'] ?></h1>

    <table class="min-w-full overflow-hidden bg-white border border-gray-300 rounded-lg">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 border">Nama Makanan</th>
          <th class="px-4 py-2 border">Deskripsi</th>
          <th class="px-4 py-2 border">Tanggal</th>
          <th class="px-4 py-2 border">Waktu Makan</th>
          <th class="px-4 py-2 border">Porsi</th>
          <th class="px-4 py-2 border">Nutrisi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // DATA DUMMY
        $data = $_SESSION['makanan_user'];

        // LOOPING DATA DUMMY
        foreach ($data as $makanan) {
          echo "<tr class='border-t'>";
          echo "<td class='px-4 py-2 border'>{$makanan['nama_makanan']}</td>";
          echo "<td class='px-4 py-2 border'>{$makanan['deskripsi']}</td>";
          echo "<td class='px-4 py-2 border'>{$makanan['tanggal']}</td>";
          echo "<td class='px-4 py-2 border'>{$makanan['waktu_makan']}</td>";
          echo "<td class='px-4 py-2 border'>{$makanan['jumlah_porsi']}</td>";
          echo "<td class='px-4 py-2 border'>";
          echo "<ul class='pl-5'>";
          foreach ($makanan['nutrisi'] as $nutrisi) {
            echo "<li>" . $nutrisi[1] . " " . $nutrisi[2] . " - " . $nutrisi[0] . "</li>";
          }
          echo "</ul>";
          echo "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>