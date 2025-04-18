<?php
$perPage = 10;
$total = count($foods);
$totalPages = $foods[1];

// Ambil halaman aktif
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$page = min($page, $totalPages);

// Hitung offset
$start = ($page - 1) * $perPage;
$currentFoods = $foods[0];
?>

<div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md">
  <table class="min-w-full bg-white divide-y divide-gray-200">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">No</th>
        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Nama Makanan</th>
      </tr>
    </thead>
    <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
      <?php if (empty($currentFoods)): ?>
        <tr>
          <td colspan="2" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
        </tr>
      <?php else: ?>
        <?php $no = $start + 1; ?>
        <?php foreach ($currentFoods as $food): ?>
          <tr class="transition hover:bg-gray-50" onclick="window.location='details?id=<?= $food['food_id'] ?>'">
            <td class="px-6 py-4 font-medium text-gray-800"><?= $no ?></td>
            <td class="px-6 py-4"><?= htmlspecialchars($food['nama_makanan']) ?></td>
          </tr>
          <?php $no++; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
  <div class="flex flex-wrap gap-2 mt-4">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?search=<?= urlencode($_GET['search'] ?? '') ?>&page=<?= $i ?>"
        class="px-4 py-2 text-sm rounded-lg border 
         <?= $i == $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' ?> 
         transition">
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<script>

</script>