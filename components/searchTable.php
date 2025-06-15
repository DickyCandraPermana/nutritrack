<div class="px-16 py-12">

    <h1 class="mb-6 text-3xl font-bold text-green-700">🍽️ Cari Makanan</h1>

    <form method="get" class="flex items-center gap-2 mb-6">
        <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>"
            class="w-2/3 px-4 py-2 border border-green-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400"
            placeholder="Cari nama atau deskripsi makanan...">
        <button type="submit"
            class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
            Cari
        </button>
    </form>

    <table class="min-w-full border-collapse table-auto max-w-[80%] mx-auto">
        <thead class="text-sm text-left text-black bg-green-100">
            <tr>
                <th class="p-3 border-b border-green-200">No</th>
                <th class="p-3 border-b border-green-200">Nama Makanan</th>
                <th class="p-3 border-b border-green-200">Deskripsi</th>
                <th class="p-3 border-b border-green-200">Detail</th>
            </tr>
        </thead>

        <tbody class="text-sm text-gray-700">
            <?php if (isset($paginated_items) && count($paginated_items)): ?>
                <?php foreach ($paginated_items as $i => $item): ?>
                    <tr class="border-b border-green-100 hover:bg-green-50">
                        <td class="p-3"><?= ($offset ?? 0) + $i + 1 ?></td>
                        <td class="p-3 font-medium text-black-800"><?= htmlspecialchars($item['nama']) ?></td>
                        <td class="p-3"><?= empty($item['deskripsi']) ? '<span class="italic text-gray-500">Tidak ada deskripsi</span>' : htmlspecialchars($item['deskripsi']) ?></td>
                        <td class="p-3">
                            <a href="details?id=<?= htmlspecialchars($item['id']) ?>"
                                class="text-green-600 hover:underline">Lihat Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">Tidak ada hasil ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div class="flex justify-center mt-6 space-x-2">
            <?php
            $range = 2; // Number of pages to show around the current page
            $start_page = max(1, ($current_page ?? 1) - $range);
            $end_page = min($total_pages, ($current_page ?? 1) + $range);

            // Always show the first page
            if ($start_page > 1) {
            ?>
                <a href="?q=<?= htmlspecialchars($keyword ?? '') ?>&page=1"
                    class="px-4 py-2 rounded border <?= (1 == ($current_page ?? 1)) ? 'bg-green-500 text-white' : 'bg-white text-green-700 border-green-300 hover:bg-green-100' ?>">
                    1
                </a>
                <?php
                if ($start_page > 2) {
                    echo '<span class="px-4 py-2">...</span>';
                }
            }

            // Show pages around the current page
            for ($page = $start_page; $page <= $end_page; $page++): ?>
                <a href="?q=<?= htmlspecialchars($keyword ?? '') ?>&page=<?= $page ?>"
                    class="px-4 py-2 rounded border <?= ($page == ($current_page ?? 1)) ? 'bg-green-500 text-white' : 'bg-white text-green-700 border-green-300 hover:bg-green-100' ?>">
                    <?= $page ?>
                </a>
            <?php endfor;

            // Always show the last page
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<span class="px-4 py-2">...</span>';
                }
            ?>
                <a href="?q=<?= htmlspecialchars($keyword ?? '') ?>&page=<?= $total_pages ?>"
                    class="px-4 py-2 rounded border <?= ($total_pages == ($current_page ?? 1)) ? 'bg-green-500 text-white' : 'bg-white text-green-700 border-green-300 hover:bg-green-100' ?>">
                    <?= $total_pages ?>
                </a>
            <?php
            }
            ?>
        </div>
    <?php endif; ?>

</div>