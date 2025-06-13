<?php
if (!isset($details) || empty($details)) {
    echo "<div class='max-w-xl p-6 mx-auto text-center text-red-500 bg-white shadow-md rounded-xl'>Data makanan tidak ditemukan.</div>";
    return;
}

$foodName = htmlspecialchars($details[0]['makanan']);
$nutritionDataByName = []; // Keep this for the main table
$nutritionDataById = [];   // New array for ID-based lookup

foreach ($details as $item) {
    $nutritionDataByName[$item['nutrisi']] = [
        'jumlah' => htmlspecialchars($item['jumlah']),
        'satuan' => htmlspecialchars($item['satuan']),
        'nutrition_id' => htmlspecialchars($item['nutrition_id'])
    ];
    $nutritionDataById[$item['nutrition_id']] = [
        'jumlah' => htmlspecialchars($item['jumlah']),
        'satuan' => htmlspecialchars($item['satuan']),
        'nutrisi' => htmlspecialchars($item['nutrisi'])
    ];
}

// Helper function to get nutrition value safely by name
function getNutritionValueByName($nutritionDataByName, $key, $default = 'N/A')
{
    return isset($nutritionDataByName[$key]) ? $nutritionDataByName[$key]['jumlah'] . ' ' . $nutritionDataByName[$key]['satuan'] : $default;
}

function getNutritionAmountByName($nutritionDataByName, $key, $default = 'N/A')
{
    return isset($nutritionDataByName[$key]) ? $nutritionDataByName[$key]['jumlah'] : $default;
}

function getNutritionUnitByName($nutritionDataByName, $key, $default = '')
{
    return isset($nutritionDataByName[$key]) ? $nutritionDataByName[$key]['satuan'] : $default;
}

// Helper function to get nutrition value safely by ID
function getNutritionValueById($nutritionDataById, $id, $default = 'N/A')
{
    return isset($nutritionDataById[$id]) ? $nutritionDataById[$id]['jumlah'] . ' ' . $nutritionDataById[$id]['satuan'] : $default;
}

function getNutritionAmountById($nutritionDataById, $id, $default = 'N/A')
{
    return isset($nutritionDataById[$id]) ? $nutritionDataById[$id]['jumlah'] : $default;
}

function getNutritionUnitById($nutritionDataById, $id, $default = '')
{
    return isset($nutritionDataById[$id]) ? $nutritionDataById[$id]['satuan'] : $default;
}

// Placeholder for %AKG calculation - this would require a separate AKG model/data
// Using nutrition_id for more robust mapping
function calculateAKG($nutritionId, $amount, $unit)
{
    // Reference AKG values based on common guidelines (e.g., Peraturan Menteri Kesehatan No. 28 Tahun 2019 tentang Angka Kecukupan Gizi)
    // These are simplified for demonstration and may need to be adjusted for specific demographics.
    // Values are for adults, daily.
    $akg_values_by_id = [
        1 => ['name' => 'Energi', 'value' => 2150, 'unit' => 'kkal'], // Kalori
        2 => ['name' => 'Lemak total', 'value' => 67, 'unit' => 'g'],
        3 => ['name' => 'Vitamin A', 'value' => 600, 'unit' => 'mcg'],
        4 => ['name' => 'Vitamin B1', 'value' => 1.1, 'unit' => 'mg'],
        5 => ['name' => 'Vitamin B2', 'value' => 1.1, 'unit' => 'mg'],
        6 => ['name' => 'Karbohidrat total', 'value' => 325, 'unit' => 'g'],
        7 => ['name' => 'Vitamin B3', 'value' => 15, 'unit' => 'mg'],
        8 => ['name' => 'Protein', 'value' => 60, 'unit' => 'g'],
        9 => ['name' => 'Serat pangan', 'value' => 30, 'unit' => 'g'],
        10 => ['name' => 'Kalsium', 'value' => 1100, 'unit' => 'mg'],
        11 => ['name' => 'Fosfor', 'value' => 700, 'unit' => 'mg'],
        12 => ['name' => 'Natrium', 'value' => 1500, 'unit' => 'mg'],
        13 => ['name' => 'Kalium', 'value' => 4700, 'unit' => 'mg'],
        14 => ['name' => 'Vitamin C', 'value' => 90, 'unit' => 'mg'],
        // Add more AKG values as needed, matching nutrition_id from your database
    ];

    if (isset($akg_values_by_id[$nutritionId])) {
        $ref_data = $akg_values_by_id[$nutritionId];
        $ref_value = $ref_data['value'];
        $ref_unit = $ref_data['unit'];

        $converted_amount = (float)$amount;

        // Simple unit conversions (adjust as needed for more precision)
        if ($unit === 'mg' && $ref_unit === 'g') {
            $converted_amount /= 1000;
        } elseif ($unit === 'mcg' && $ref_unit === 'mg') {
            $converted_amount /= 1000;
        } elseif ($unit === 'g' && $ref_unit === 'mg') {
            $converted_amount *= 1000;
        } elseif ($unit === 'mg' && $ref_unit === 'mcg') {
            $converted_amount *= 1000;
        }

        if ($ref_value > 0) {
            return round(($converted_amount / $ref_value) * 100, 2) . '%';
        }
    }
    return '0%'; // Default to 0% if AKG value not found or cannot be calculated
}

?>

<div class="max-w-6xl p-6 mx-auto">
    <h1 class="mb-6 text-3xl font-bold text-center text-gray-800"><?= $foodName ?></h1>
    <div class="grid max-w-6xl grid-cols-1 gap-6 mx-auto lg:grid-cols-3">
        <!-- Informasi Nilai Gizi -->
        <div class="col-span-1 p-4 bg-white rounded shadow">
            <h2 class="pb-2 mb-4 text-xl font-bold border-b">Informasi Nilai Gizi</h2>
            <table class="w-full text-sm text-gray-700">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="py-1">Zat Gizi</th>
                        <th class="py-1 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nutrisi']) ?></td>
                            <td class="text-right"><?= htmlspecialchars($item['jumlah']) . ' ' . htmlspecialchars($item['satuan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Gizi -->
        <div class="flex flex-col col-span-2 gap-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="p-4 text-center text-blue-800 bg-blue-100 rounded shadow">
                    <div class="text-sm">Energi</div>
                    <div class="text-xl font-bold"><?= getNutritionAmountById($nutritionDataById, 1) ?> <span class="text-sm"><?= getNutritionUnitById($nutritionDataById, 1) ?></span></div>
                </div>
                <div class="p-4 text-center text-yellow-800 bg-yellow-100 rounded shadow">
                    <div class="text-sm">Lemak</div>
                    <div class="text-xl font-bold"><?= getNutritionAmountById($nutritionDataById, 2) ?> <span class="text-sm"><?= getNutritionUnitById($nutritionDataById, 2) ?></span></div>
                </div>
                <div class="p-4 text-center text-red-800 bg-red-100 rounded shadow">
                    <div class="text-sm">Protein</div>
                    <div class="text-xl font-bold"><?= getNutritionAmountById($nutritionDataById, 8) ?> <span class="text-sm"><?= getNutritionUnitById($nutritionDataById, 8) ?></span></div>
                </div>
                <div class="p-4 text-center text-pink-800 bg-pink-100 rounded shadow">
                    <div class="text-sm">Karbo</div>
                    <div class="text-xl font-bold"><?= getNutritionAmountById($nutritionDataById, 6) ?> <span class="text-sm"><?= getNutritionUnitById($nutritionDataById, 6) ?></span></div>
                </div>
            </div>

            <!-- Zat Gizi Unggulan -->
            <div class="p-4 bg-white rounded shadow">
                <h2 class="mb-3 text-lg font-semibold">Zat Gizi Unggulan Dalam Produk Ini</h2>
                <div class="flex flex-wrap gap-4">
                    <?php
                    $featuredNutrients = [];
                    $tempNutrients = [];
                    foreach ($details as $item) {
                        $nutrientName = htmlspecialchars($item['nutrisi']);
                        $nutritionId = htmlspecialchars($item['nutrition_id']);
                        $amount = (float)htmlspecialchars($item['jumlah']); // Convert to float for numerical comparison
                        $unit = htmlspecialchars($item['satuan']);

                        // Abaikan kalori (nutrition_id = 1)
                        if ($nutritionId == 1) {
                            continue;
                        }
                        
                        // For display, we can still calculate AKG if needed, but sorting is by amount
                        $akg = calculateAKG($nutritionId, $amount, $unit);

                        // Store the nutrient with its amount for sorting
                        $tempNutrients[] = [
                            'name' => $nutrientName,
                            'amount' => $amount,
                            'unit' => $unit,
                            'akg' => $akg // Keep AKG for display if desired
                        ];
                    }

                    // Sort by amount in descending order
                    usort($tempNutrients, function ($a, $b) {
                        return $b['amount'] <=> $a['amount'];
                    });

                    // Pick top 3
                    $featuredNutrients = array_slice($tempNutrients, 0, 3);
                    
                    if (empty($featuredNutrients)) {
                        echo "<p class='text-gray-500'>Tidak ada zat gizi unggulan yang dapat ditampilkan.</p>";
                    } else {
                        foreach ($featuredNutrients as $index => $fn) {
                            echo "<div class='px-4 py-2 text-green-800 bg-green-100 rounded shadow'>";
                            echo "<div class='text-sm font-semibold'>" . ($index + 1) . ". " . $fn['name'] . "</div>";
                            // Display amount and unit, or AKG if preferred for "unggulan"
                            echo "<div class='text-xs'>" . $fn['amount'] . " " . $fn['unit'] . " (" . $fn['akg'] . ")</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Grafik AKG -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 text-center bg-white rounded shadow">
                    <div class="mb-2 text-sm">
                        <?php
                        $energiAKG = calculateAKG(1, getNutritionAmountById($nutritionDataById, 1), getNutritionUnitById($nutritionDataById, 1));
                        echo $energiAKG . ' AKG';
                        ?>
                    </div>
                    <div class="relative w-24 h-24 mx-auto">
                        <!-- Placeholder donut -->
                        <div class="absolute inset-0 border-8 border-pink-300 rounded-full border-t-white"></div>
                    </div>
                </div>
                <div class="p-4 text-center bg-white rounded shadow">
                    <div class="mb-2 text-sm">Berat Dapat Dimakan</div>
                    <div class="relative w-24 h-24 mx-auto">
                        <!-- Placeholder pie -->
                        <div class="absolute inset-0 bg-green-500 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
