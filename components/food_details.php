<div class="max-w-xl p-6 mx-auto bg-white shadow-md rounded-xl">
  <h2 class="mb-4 text-2xl font-bold text-gray-800">
    <?= htmlspecialchars($details[0]['makanan']) ?>
  </h2>

  <div class="space-y-3">
    <?php foreach ($details as $item): ?>
      <div class="p-4 bg-gray-100 rounded-lg shadow-sm">
        <div class="flex justify-between">
          <span class="text-sm font-medium text-gray-600"><?= htmlspecialchars($item['nutrisi']) ?></span>
          <span class="text-sm text-gray-800"><?= htmlspecialchars($item['jumlah']) . ' ' . htmlspecialchars($item['satuan']) ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>