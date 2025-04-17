<?php

// Konfigurasi
$perPage = 10;
$total = count($foods);
$totalPages = $foods[1];

// Dapatkan halaman aktif
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$page = min($page, $totalPages);

// Hitung offset
$start = ($page - 1) * $perPage;
$currentFoods = $foods[0];
?>

<h2>Daftar Makanan (Halaman <?= $page ?>)</h2>

<table border="1" width="100%" cellpadding="10">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Makanan</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (empty($currentFoods)) {
      echo "<tr><td colspan='2'>Tidak ada data</td></tr>";
    } else {
      $no = $start + 1;
      foreach ($currentFoods as $food) {
        echo "<tr>
                <td>{$no}</td>
                <td>" . htmlspecialchars($food['nama_makanan']) . "</td>
              </tr>";
        $no++;
      }
    }
    ?>
  </tbody>
</table>

<div style="margin-top: 20px;">
  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>"
      style="padding: 5px 10px; margin-right: 5px; background-color: <?= $i == $foods[1] ? '#ddd' : '#eee' ?>;">
      <?= $i ?>
    </a>
  <?php endfor; ?>