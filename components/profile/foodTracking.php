<div class="container px-4 mx-auto mt-6">
  <!-- Header & Date Selector -->
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold">Tracking Gizi</h2>
    <div class="flex items-center space-x-3">
      <button class="px-2 py-1 text-gray-500 border rounded hover:bg-gray-100">
        <i class="fas fa-chevron-left"></i>
      </button>
      <h5 class="text-lg font-medium">Selasa, 18 Maret 2025</h5>
      <button class="px-2 py-1 text-gray-500 border rounded hover:bg-gray-100">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
    <!-- Left Column -->
    <div class="space-y-6 lg:col-span-8">
      <!-- Daily Summary -->
      <div class="p-6 bg-white rounded-lg shadow">
        <div class="grid items-center grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <h5 class="mb-2 text-lg font-medium">Total Kalori Hari Ini</h5>
            <div class="flex items-end mb-2">
              <h2 class="mr-2 text-3xl font-bold">1,450</h2>
              <h4 class="text-xl text-gray-400">/ 2,000 kkal</h4>
            </div>
            <div class="w-full h-3 bg-gray-200 rounded-full">
              <div class="h-3 bg-yellow-400 rounded-full" style="width: 72.5%"></div>
            </div>
            <p class="mt-2 text-sm text-gray-600">Anda masih membutuhkan 550 kkal untuk mencapai target</p>
          </div>
          <div class="relative">
            <canvas id="calorieGauge"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <h6 class="text-xl font-semibold">72.5%</h6>
              <small class="text-sm text-gray-500">dari target</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Nutrient Breakdown -->
      <div class="flex flex-col p-6 bg-white rounded-lg shadow">
        <div class="flex items-center mb-4 text-lg font-semibold">
          <i class="mr-2 text-gray-600 fas fa-chart-pie"></i> Distribusi Nutrisi
        </div>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
          <div class="space-y-4 lg:col-span-5">
            <!-- Karbohidrat -->
            <div class="flex items-start space-x-3">
              <div class="p-2 text-white bg-blue-500 rounded-full">
                <i class="fas fa-bread-slice"></i>
              </div>
              <div class="flex-1">
                <h6 class="mb-1 font-medium">Karbohidrat</h6>
                <div class="flex justify-between text-sm">
                  <span>180g / 250g</span><span>72%</span>
                </div>
                <div class="w-full h-2 mt-1 bg-gray-200 rounded-full">
                  <div class="h-2 bg-blue-500 rounded-full" style="width: 72%"></div>
                </div>
              </div>
            </div>
            <!-- Protein -->
            <div class="flex items-start space-x-3">
              <div class="p-2 text-white bg-green-500 rounded-full">
                <i class="fas fa-drumstick-bite"></i>
              </div>
              <div class="flex-1">
                <h6 class="mb-1 font-medium">Protein</h6>
                <div class="flex justify-between text-sm">
                  <span>85g / 120g</span><span>71%</span>
                </div>
                <div class="w-full h-2 mt-1 bg-gray-200 rounded-full">
                  <div class="h-2 bg-green-500 rounded-full" style="width: 71%"></div>
                </div>
              </div>
            </div>
            <!-- Lemak -->
            <div class="flex items-start space-x-3">
              <div class="p-2 text-white bg-yellow-500 rounded-full">
                <i class="fas fa-oil-can"></i>
              </div>
              <div class="flex-1">
                <h6 class="mb-1 font-medium">Lemak</h6>
                <div class="flex justify-between text-sm">
                  <span>45g / 65g</span><span>69%</span>
                </div>
                <div class="w-full h-2 mt-1 bg-gray-200 rounded-full">
                  <div class="h-2 bg-yellow-500 rounded-full" style="width: 69%"></div>
                </div>
              </div>
            </div>
            <!-- Serat -->
            <div class="flex items-start space-x-3">
              <div class="p-2 text-white rounded-full bg-cyan-500">
                <i class="fas fa-seedling"></i>
              </div>
              <div class="flex-1">
                <h6 class="mb-1 font-medium">Serat</h6>
                <div class="flex justify-between text-sm">
                  <span>18g / 30g</span><span>60%</span>
                </div>
                <div class="w-full h-2 mt-1 bg-gray-200 rounded-full">
                  <div class="h-2 rounded-full bg-cyan-500" style="width: 60%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Riwayat Makanan -->
      <div class="mb-6 bg-white rounded-lg shadow">
        <div class="flex items-center justify-between px-4 py-3 border-b">
          <h5 class="flex items-center text-lg font-semibold">
            <i class="mr-2 fas fa-utensils"></i> Riwayat Makanan
          </h5>
          <button class="flex items-center px-3 py-2 text-xs text-white rounded-md bg-cyan-500">
            <i class="mr-1 fas fa-plus"></i> Tambah Makanan
          </button>
        </div>

        <div class="px-4 py-5">
          <!-- Breakfast -->
          <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
              <h6 class="px-2 py-1 text-sm font-semibold text-gray-700 bg-yellow-200 rounded-full">Sarapan</h6>
              <span class="text-gray-600">425 kkal</span>
            </div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h6 class="text-sm font-semibold text-gray-700">nasi goreng telur</h6>
                <small class="text-gray-500">1 mangkuk (250g)</small>
              </div>
              <div class="text-right">
                <span class="block text-gray-700">320 kkal</span>
                <div class="flex mt-1 space-x-2">
                  <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: 45g</span>
                  <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: 12g</span>
                  <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: 8g</span>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h6 class="text-sm font-semibold text-gray-700">Susu</h6>
                <small class="text-gray-500">1 gelas (200ml)</small>
              </div>
              <div class="text-right">
                <span class="block text-gray-700">105 kkal</span>
                <div class="flex mt-1 space-x-2">
                  <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: 12g</span>
                  <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: 8g</span>
                  <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: 2.5g</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Lunch -->
          <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
              <h6 class="px-2 py-1 text-sm font-semibold text-gray-700 bg-yellow-200 rounded-full">Makan Siang</h6>
              <span class="text-gray-600">625 kkal</span>
            </div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h6 class="text-sm font-semibold text-gray-700">Nasi kuning</h6>
                <small class="text-gray-500">1 porsi (150g)</small>
              </div>
              <div class="text-right">
                <span class="block text-gray-700">180 kkal</span>
                <div class="flex mt-1 space-x-2">
                  <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: 40g</span>
                  <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: 4g</span>
                  <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: 1g</span>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h6 class="text-sm font-semibold text-gray-700">Mie ayam</h6>
                <small class="text-gray-500">1 mangkuk 240g</small>
              </div>
              <div class="text-right">
                <span class="block text-gray-700">480 kkal</span>
                <div class="flex mt-1 space-x-2">
                  <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: 0g</span>
                  <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: 35g</span>
                  <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: 8g</span>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h6 class="text-sm font-semibold text-gray-700">Sop buah</h6>
                <small class="text-gray-500">1 mangkuk (200g)</small>
              </div>
              <div class="text-right">
                <span class="block text-gray-700">125 kkal</span>
                <div class="flex mt-1 space-x-2">
                  <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: 15g</span>
                  <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: 2g</span>
                  <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: 4g</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Dinner -->
          <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
              <h6 class="px-2 py-1 text-sm font-semibold text-gray-700 bg-yellow-200 rounded-full">Makan Malam</h6>
              <span class="text-gray-600">400 kkal</span>
            </div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h6 class="text-sm font-semibold text-gray-700">Roti Bakar</h6>
                <small class="text-gray-500">3 potong (210g)</small>
              </div>
              <div class="text-right">
                <span class="block text-gray-700">500 kkal</span>
                <div class="flex mt-1 space-x-2">
                  <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: 20g</span>
                  <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: 3g</span>
                  <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: 15g</span>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h6 class="text-sm font-semibold text-gray-700">Tempe goreng</h6>
                <small class="text-gray-500">1 buah sedang (150g)</small>
              </div>
              <div class="text-right">
                <span class="block text-gray-700">120 kkal</span>
                <div class="flex mt-1 space-x-2">
                  <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: 27g</span>
                  <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: 3g</span>
                  <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: 0g</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Right Column -->
    <div class="space-y-6 lg:col-span-4">
      <!-- Konsumsi Air -->
      <div class="mb-6 bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b">
          <h5 class="flex items-center text-lg font-semibold">
            <i class="mr-2 text-blue-500 fas fa-tint"></i> Konsumsi Air
          </h5>
        </div>
        <div class="px-4 py-5 text-center">
          <h6 class="mb-2 text-sm text-gray-600">Target: 8 gelas (2000ml)</h6>
          <div class="w-full h-3 mb-2 bg-gray-200 rounded-full">
            <div class="h-3 rounded-full bg-cyan-400" style="width: 62.5%"></div>
          </div>
          <p class="mb-4 text-sm text-gray-700">5 dari 8 gelas (1250ml)</p>

          <div class="flex items-center justify-between mb-4">
            <i class="text-xl fas fa-glass-water text-cyan-500"></i>
            <i class="text-xl fas fa-glass-water text-cyan-500"></i>
            <i class="text-xl fas fa-glass-water text-cyan-500"></i>
            <i class="text-xl fas fa-glass-water text-cyan-500"></i>
            <i class="text-xl fas fa-glass-water text-cyan-500"></i>
            <i class="text-xl text-gray-400 fas fa-glass-water"></i>
            <i class="text-xl text-gray-400 fas fa-glass-water"></i>
            <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          </div>

          <button class="w-full px-4 py-2 font-medium text-white rounded bg-cyan-500 hover:bg-cyan-600">
            <i class="mr-2 fas fa-plus"></i> Tambah Air
          </button>
        </div>
      </div>

      <!-- Tren Nutrisi -->
      <div class="mb-6 bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b">
          <h5 class="flex items-center text-lg font-semibold">
            <i class="mr-2 text-green-500 fas fa-chart-line"></i> Tren Nutrisi
          </h5>
        </div>
        <div class="px-4 py-5">
          <!-- Tab Navigation -->
          <ul class="flex mb-4 border-b">
            <li class="mr-2">
              <button class="px-4 py-2 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:text-cyan-500 hover:border-cyan-500 active" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly" type="button" role="tab">
                Minggu Ini
              </button>
            </li>
            <li class="mr-2">
              <button class="px-4 py-2 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:text-cyan-500 hover:border-cyan-500" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">
                Bulanan
              </button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content">
            <!-- Minggu Ini -->
            <div class="tab-pane fade show active" id="weekly" role="tabpanel">
              <canvas id="weeklyTrendChart" height="250"></canvas>
            </div>
            <!-- Bulanan -->
            <div class="tab-pane fade" id="monthly" role="tabpanel">
              <canvas id="monthlyTrendChart" height="250"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Pengingat -->
      <div class="mb-6 bg-white rounded-lg shadow">
        <div class="flex items-center justify-between px-4 py-3 border-b">
          <h5 class="flex items-center text-lg font-semibold">
            <i class="mr-2 fas fa-bell"></i> Pengingat
          </h5>
          <button class="flex items-center px-3 py-2 text-xs text-white rounded-md bg-cyan-500">
            <i class="mr-1 fas fa-plus"></i> Tambah Pengingat
          </button>
        </div>

        <div class="px-4 py-5">
          <!-- Reminder 1 -->
          <div class="flex items-center justify-between mb-4">
            <div>
              <h6 class="text-sm font-semibold text-gray-700">Minum Air</h6>
              <small class="text-gray-500">10:00 AM</small>
            </div>
            <div class="flex space-x-2">
              <button class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Aktif</button>
              <button class="px-2 py-1 text-xs text-white bg-red-500 rounded-full">Hapus</button>
            </div>
          </div>

          <!-- Reminder 2 -->
          <div class="flex items-center justify-between mb-4">
            <div>
              <h6 class="text-sm font-semibold text-gray-700">Sarapan</h6>
              <small class="text-gray-500">07:30 AM</small>
            </div>
            <div class="flex space-x-2">
              <button class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Aktif</button>
              <button class="px-2 py-1 text-xs text-white bg-red-500 rounded-full">Hapus</button>
            </div>
          </div>

          <!-- Reminder 3 -->
          <div class="flex items-center justify-between mb-4">
            <div>
              <h6 class="text-sm font-semibold text-gray-700">Makan Siang</h6>
              <small class="text-gray-500">12:00 PM</small>
            </div>
            <div class="flex space-x-2">
              <button class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Aktif</button>
              <button class="px-2 py-1 text-xs text-white bg-red-500 rounded-full">Hapus</button>
            </div>
          </div>

          <!-- Reminder 4 -->
          <div class="flex items-center justify-between mb-4">
            <div>
              <h6 class="text-sm font-semibold text-gray-700">Makan Malam</h6>
              <small class="text-gray-500">07:00 PM</small>
            </div>
            <div class="flex space-x-2">
              <button class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Aktif</button>
              <button class="px-2 py-1 text-xs text-white bg-red-500 rounded-full">Hapus</button>
            </div>
          </div>
        </div>
      </div>

      <?php /*
      <!-- Rekomendasi Gizi -->
      <div class="p-5 mb-6 bg-white rounded-lg shadow-lg">
        <div class="mb-5 text-center">
          <h2 class="text-xl font-semibold text-gray-800">Rekomendasi Gizi Harian</h2>
          <p class="text-sm text-gray-500">Berikut adalah beberapa tips dan rekomendasi untuk menjaga pola makan sehat dan bergizi setiap hari.</p>
        </div>

        <div class="space-y-4">
          <!-- Rekomendasi 1: Sarapan Sehat -->
          <div class="flex items-center p-4 space-x-4 border rounded-lg bg-gray-50">
            <div class="flex-shrink-0">
              <i class="text-3xl text-yellow-500 fas fa-egg"></i>
            </div>
            <div class="flex-grow">
              <h4 class="text-lg font-medium text-gray-800">Sarapan Sehat</h4>
              <p class="text-sm text-gray-600">Mulailah hari dengan sarapan kaya protein dan serat, seperti telur rebus, oatmeal, dan buah-buahan segar.</p>
            </div>
          </div>

          <!-- Rekomendasi 2: Makan Siang Seimbang -->
          <div class="flex items-center p-4 space-x-4 border rounded-lg bg-gray-50">
            <div class="flex-shrink-0">
              <i class="text-3xl text-green-500 fas fa-utensils"></i>
            </div>
            <div class="flex-grow">
              <h4 class="text-lg font-medium text-gray-800">Makan Siang Seimbang</h4>
              <p class="text-sm text-gray-600">Sajikan makanan dengan kombinasi karbohidrat, protein, dan sayuran. Cobalah nasi merah dengan ayam panggang dan salad hijau.</p>
            </div>
          </div>

          <!-- Rekomendasi 3: Camilan Sehat -->
          <div class="flex items-center p-4 space-x-4 border rounded-lg bg-gray-50">
            <div class="flex-shrink-0">
              <i class="text-3xl text-red-500 fas fa-apple-alt"></i>
            </div>
            <div class="flex-grow">
              <h4 class="text-lg font-medium text-gray-800">Camilan Sehat</h4>
              <p class="text-sm text-gray-600">Pilih camilan kaya nutrisi seperti buah-buahan segar, kacang-kacangan, atau yogurt rendah lemak.</p>
            </div>
          </div>

          <!-- Rekomendasi 4: Minum Air yang Cukup -->
          <div class="flex items-center p-4 space-x-4 border rounded-lg bg-gray-50">
            <div class="flex-shrink-0">
              <i class="text-3xl text-blue-500 fas fa-tint"></i>
            </div>
            <div class="flex-grow">
              <h4 class="text-lg font-medium text-gray-800">Minum Air yang Cukup</h4>
              <p class="text-sm text-gray-600">Pastikan untuk minum setidaknya 8 gelas air setiap hari untuk menjaga hidrasi tubuh dan kesehatan kulit.</p>
            </div>
          </div>

          <!-- Rekomendasi 5: Konsumsi Makanan Kaya Serat -->
          <div class="flex items-center p-4 space-x-4 border rounded-lg bg-gray-50">
            <div class="flex-shrink-0">
              <i class="text-3xl text-green-700 fas fa-leaf"></i>
            </div>
            <div class="flex-grow">
              <h4 class="text-lg font-medium text-gray-800">Konsumsi Makanan Kaya Serat</h4>
              <p class="text-sm text-gray-600">Makanan kaya serat seperti sayuran hijau, buah-buahan, dan biji-bijian membantu pencernaan dan mencegah konstipasi.</p>
            </div>
          </div>
        </div>
      </div> */ ?>

    </div>
  </div>
</div>