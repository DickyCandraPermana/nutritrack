<?php
$currentDate = date('l, j F Y');

$total_calories = $data['total_calories'] ?? 0;
$target_calories = $data['target_calories'];
$calories_deff = $target_calories - $total_calories;
$calories_percent = number_format(($total_calories / $target_calories) * 100, 2);

if ($calories_percent > 100) {
  $calories_percent = 100;
}

$carbs = number_format($target_calories * 0.5 / 4, 2);
$protein = number_format($target_calories * 0.2 / 4, 2);
$fat = number_format($target_calories * 0.3 / 9, 2);
$fiber = (int) ($target_calories / 1000) * 14;

require 'addReminder.php';
?>

<div class="container px-6 mx-auto mt-6">
  <!-- Header & Date Selector -->
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold">Tracking Gizi</h2>
    <div class="flex items-center space-x-3">
      <button class="px-2 py-1 text-gray-500 border rounded hover:bg-gray-100">
        <i class="fas fa-chevron-left"></i>
      </button>
      <h5 class="text-lg font-medium"><?= $currentDate ?></h5>
      <button class="px-2 py-1 text-gray-500 border rounded hover:bg-gray-100">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>

  <!-- Rekomendasi Gizi -->
  <?php
  $recommendations = [
    [
      "icon" => "fas fa-egg",
      "iconColor" => "text-yellow-500",
      "title" => "Sarapan Sehat",
      "description" => "Mulailah hari dengan sarapan kaya protein dan serat, seperti telur rebus, oatmeal, dan buah-buahan segar."
    ],
    [
      "icon" => "fas fa-utensils",
      "iconColor" => "text-green-500",
      "title" => "Makan Siang Seimbang",
      "description" => "Sajikan makanan dengan kombinasi karbohidrat, protein, dan sayuran. Cobalah nasi merah dengan ayam panggang dan salad hijau."
    ],
    [
      "icon" => "fas fa-apple-alt",
      "iconColor" => "text-red-500",
      "title" => "Camilan Sehat",
      "description" => "Pilih camilan kaya nutrisi seperti buah-buahan segar, kacang-kacangan, atau yogurt rendah lemak."
    ],
    [
      "icon" => "fas fa-tint",
      "iconColor" => "text-blue-500",
      "title" => "Minum Air yang Cukup",
      "description" => "Pastikan untuk minum setidaknya 8 gelas air setiap hari untuk menjaga hidrasi tubuh dan kesehatan kulit."
    ],
    [
      "icon" => "fas fa-leaf",
      "iconColor" => "text-green-700",
      "title" => "Konsumsi Makanan Kaya Serat",
      "description" => "Makanan kaya serat seperti sayuran hijau, buah-buahan, dan biji-bijian membantu pencernaan dan mencegah konstipasi."
    ]
  ];
  ?>


  <div class="flex flex-row flex-wrap justify-center gap-4 mb-6 space-y-4">
    <?php foreach ($recommendations as $item): ?>
      <div class="flex items-center p-4 space-x-4 border rounded-lg shadow-md w-[400px] bg-gray-50">
        <div class="flex-shrink-0">
          <i class="text-3xl <?= $item['iconColor'] ?> <?= $item['icon'] ?>"></i>
        </div>
        <div class="flex-grow">
          <h4 class="text-lg font-medium text-gray-800"><?= htmlspecialchars($item['title']) ?></h4>
          <p class="text-sm text-gray-600"><?= htmlspecialchars($item['description']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
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
            <h2 class="mr-2 text-2xl font-bold"><?= $total_calories ?> kkal</h2>
            <h4 class="text-lg text-gray-400">/ <?= $target_calories ?> kkal</h4>
          </div>
          <div class="w-full h-3 bg-gray-200 rounded-full">
            <div class="h-3 bg-yellow-400 rounded-full" style="width: <?= $calories_percent ?>%"></div>
          </div>
          <p class="mt-2 text-sm text-gray-600">Anda masih membutuhkan <?= $calories_deff ?> kkal untuk mencapai target</p>
        </div>
        <div class="relative">
          <canvas id="calorieGauge"></canvas>
          <div class="absolute inset-0 flex flex-col items-center justify-center">
            <h6 class="text-xl font-semibold"><?= $calories_percent  ?>%</h6>
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
      <!-- Item nutrisi -->
      <div id="nutrient-container" class="space-y-4 lg:col-span-5"></div>
    </div>

    <!-- Riwayat Makanan -->
    <div id="mealHistoryContainer" class="mb-6 bg-white rounded-lg shadow"></div>

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
          <div class="h-3 rounded-full bg-cyan-400" style="width: 0%"></div>
        </div>
        <p class="mb-4 text-sm text-gray-700">0 dari 8 gelas (0ml)</p>

        <div class="flex items-center justify-between mb-4">
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
          <i class="text-xl text-gray-400 fas fa-glass-water"></i>
        </div>

        <div class="flex flex-row space-x-2">
          <button id="decrease-water-btn" class="w-full px-4 py-2 font-medium text-white rounded bg-cyan-500 hover:bg-cyan-600">
            <i class="mr-2 fas fa-plus"></i> Tambah Air
          </button>
        </div>
      </div>
    </div>

    <!-- Tren Nutrisi -->
    <?php
    /*
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
      */
    ?>

    <!-- Pengingat -->
    <div class="mb-6 bg-white rounded-lg shadow">
      <div class="flex items-center justify-between px-4 py-3 border-b">
        <h5 class="flex items-center text-lg font-semibold">
          <i class="mr-2 fas fa-bell"></i> Pengingat
        </h5>
        <button onclick="showModal()" class="flex items-center px-3 py-2 text-xs text-white rounded-md bg-cyan-500">
          <i class="mr-1 fas fa-plus"></i> Tambah Pengingat
        </button>
      </div>

      <div class="px-4 py-5" id="reminder-container">
        <div class="flex items-center mb-4">
          <i class="mr-2 text-gray-600 fas fa-bell"></i>
          <span class="text-sm font-semibold">Tidak ada pengingat</span>
        </div>
      </div>
    </div>

  </div>
</div>
</div>

<script>
  const nutrients = [{
      label: 'Karbohidrat',
      icon: 'fas fa-bread-slice',
      color: 'bg-blue-500',
      current: 0,
      target: <?= $carbs ?>
    },
    {
      label: 'Protein',
      icon: 'fas fa-drumstick-bite',
      color: 'bg-green-500',
      current: 0,
      target: <?= $protein ?>
    },
    {
      label: 'Lemak',
      icon: 'fas fa-oil-can',
      color: 'bg-yellow-500',
      current: 0,
      target: <?= $fat ?>
    },
    {
      label: 'Serat Pangan',
      icon: 'fas fa-seedling',
      color: 'bg-cyan-500',
      current: 0,
      target: <?= $fiber ?>
    }
  ];

  const meals = [{
      name: "Sarapan",
      totalCalories: 0,
      items: [],
    },
    {
      name: "Makan Siang",
      totalCalories: 0,
      items: [],
    },
    {
      name: "Makan Malam",
      totalCalories: 0,
      items: [],
    },
    {
      name: "Snack",
      totalCalories: 0,
      items: [],
    },
  ];

  const reminder = [];

  const container = document.getElementById('nutrient-container');

  function showModal() {
    const modal = document.getElementById('add-reminder');
    modal.classList.remove('hidden');
  }

  async function getUserReminderData() {
    reminder.length = 0;
    const response = await fetch('/nutritrack/api/get-user-reminder', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: <?= $_SESSION['user_id'] ?>
      })
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    console.log(data);

    const currentReminder = data.data;

    currentReminder.forEach(item => {
      reminder.push(item);
    })

    renderUserReminder();
  }

  function renderUserReminder() {
    const container = document.getElementById('reminder-container');
    container.innerHTML = '';

    if (reminder.length > 0) {
      reminder.forEach(item => {
        const reminderItem = document.createElement('div');
        reminderItem.className = 'flex items-center justify-between mb-4';

        // Conditionally apply strikethrough if completed
        const textClass = item.completed ?
          'text-sm font-semibold text-gray-700 line-through' :
          'text-sm font-semibold text-gray-700';

        const timeClass = item.completed ?
          'text-gray-400 line-through' :
          'text-gray-500';

        reminderItem.innerHTML = `
        <div>
          <h6 class="${textClass}">${item.judul}</h6>
          <small class="${timeClass}">${item.waktu}</small>
        </div>
        <div class="flex space-x-2">
          <button class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full" onclick="completeReminder(${item.id_reminder})">Aktif</button>
          <button class="px-2 py-1 text-xs text-white bg-red-500 rounded-full" onclick="deleteReminder(${item.id_reminder})">Hapus</button>
        </div>
      `;

        container.appendChild(reminderItem);
      });
    } else {
      container.innerHTML = `
      <div class="flex items-center mb-4">
        <i class="mr-2 text-gray-600 fas fa-bell"></i>
        <span class="text-sm font-semibold">Tidak ada pengingat</span>
      </div>
    `;
    }
  }


  async function deleteReminder(reminder_id) {
    const response = await fetch('/nutritrack/api/delete-reminder', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: <?= $_SESSION['user_id'] ?>,
        reminder_id: reminder_id
      })
    });

    if (!response.ok) {
      showFlashMessage({
        type: 'error',
        messages: 'Gagal menghapus pengingat'
      });
      return;
    }

    const data = await response.json();

    if (data.status === 'success') {
      showFlashMessage({
        type: 'success',
        messages: data.message
      });
    } else {
      showFlashMessage({
        type: 'error',
        messages: data.message
      });
    }

    getUserReminderData();
  }

  async function completeReminder(reminder_id) {
    const response = await fetch('/nutritrack/api/complete-reminder', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: <?= $_SESSION['user_id'] ?>,
        reminder_id: reminder_id
      })
    })

    if (!response.ok) {
      showFlashMessage({
        type: 'error',
        messages: 'Gagal mengedit pengingat'
      })
    }

    const data = await response.json();

    if (data.status === 'success') {
      showFlashMessage({
        type: 'success',
        messages: data.message
      });
    } else {
      showFlashMessage({
        type: 'error',
        messages: data.message
      });
    }

    getUserReminderData();
  }

  /**
   * Render ulang tampilan nutrisi di dalam container.
   * @param {Array} nutrients - Array of nutrient objects.
   * @param {string} containerId - ID dari elemen HTML tempat render.
   */
  function renderNutrientProgress(nutrients, containerId = 'nutrient-container') {
    const container = document.getElementById(containerId);
    if (!container) return console.error('Container tidak ditemukan:', containerId);

    container.innerHTML = ''; // Kosongkan dulu

    nutrients.forEach(n => {
      if (n.current > 0) {
        let percent = Math.round((n.current / n.target) * 100);
        if (percent > 100) {
          n.color = 'bg-red-600';
        };
        const item = document.createElement('div');
        item.className = 'flex items-start space-x-3';

        item.innerHTML = `
      <div class="p-2 text-white ${n.color} rounded-full">
        <i class="${n.icon}"></i>
      </div>
      <div class="flex-1">
        <h6 class="mb-1 font-medium">${n.label}</h6>
        <div class="flex justify-between text-sm">
          <span>${n.current}g / ${n.target}g</span><span>${percent}%</span>
        </div>
        <div class="w-full h-2 mt-1 bg-gray-200 rounded-full">
          <div class="h-2 ${n.color} rounded-full" style="width: ${(percent > 100) ? 100 : percent}%"></div>
        </div>
      </div>
    `;
        container.appendChild(item);
      }
    });

    if (container.innerHTML === '') {
      container.appendChild(document.createElement('p')).innerHTML = 'Belum ada data';
    }
  }

  async function getUserTrackingData(user_id, tanggal) {
    try {
      const response = await fetch('/nutritrack/api/get-user-tracking-data', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: user_id,
          tanggal: tanggal
        })
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();
      return data;
    } catch (error) {
      console.error('Error fetching user tracking data:', error);
      return null;
    }
  }

  async function updatePage() {
    try {
      const rawNutrientData = await getUserTrackingData(<?= $_SESSION["user_id"] ?>, new Date().toISOString().split('T')[0]);
      const rawMealData = await getFoodConsumed(<?= $_SESSION["user_id"] ?>, new Date().toISOString().split('T')[0]);
      getUserReminderData();

      if (rawNutrientData && rawMealData) {
        if (rawNutrientData.status === 'success') {
          const nutrientData = rawNutrientData.data;
          nutrients.forEach(nutrient => {
            const key = nutrient.label.toLowerCase();
            if (nutrientData[key] !== undefined) {
              nutrient.current = parseFloat(nutrientData[key]); // pastikan nilai numeric
            }
          });
        }

        if (rawMealData.status === 'success') {
          const mealData = rawMealData.data;
          meals.forEach(meal => {
            const matched = mealData.find(m => m.name.toLowerCase() === meal.name.toLowerCase());
            if (matched) {
              meal.totalCalories = matched.totalCalories;
              meal.items = matched.items;
            } else {
              meal.totalCalories = 0;
              meal.items = [];
            }
          });
        }

        console.log(meals);
        console.log(nutrients);

        renderMealHistory(meals);
        renderNutrientProgress(nutrients);
      }
    } catch (error) {
      console.error('Error updating page:', error);
    }
  }

  async function getFoodConsumed(user_id, tanggal) {
    try {
      const response = await fetch('/nutritrack/api/user-tracking-data', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: user_id,
          tanggal: tanggal
        })
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();
      return data;
    } catch (error) {
      console.error('Error fetching user tracking data:', error);
      return null;
    }
  }

  async function getUserGoal(user_id) {
    try {
      const response = await fetch('/nutritrack/api/get-user-goal', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: user_id
        })
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();
      return data;
    } catch (error) {
      console.error('Error fetching user tracking data:', error);
      return null;
    }
  }

  function renderMealHistory(meals) {
    const container = document.getElementById("mealHistoryContainer");

    // Filter hanya meals yang punya item
    const mealsWithItems = meals.filter(meal => meal.items && meal.items.length > 0);
    // Header
    container.innerHTML = `
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <h5 class="flex items-center text-lg font-semibold">
        <i class="mr-2 fas fa-utensils"></i> Riwayat Makanan
      </h5>
      <a href="<?= BASE_URL ?>profile/tambah-makanan" class="flex items-center px-3 py-2 text-xs text-white rounded-md bg-cyan-500">
        <i class="mr-1 fas fa-plus"></i> Tambah Makanan
      </a>
    </div>
    <div class="px-4 py-5">
      ${
        mealsWithItems.length === 0
          ? `<p class="text-sm text-center text-gray-500">Belum ada data</p>`
          : mealsWithItems
              .map(
                (meal) => `
          <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
              <h6 class="px-2 py-1 text-sm font-semibold text-gray-700 bg-yellow-200 rounded-full">${meal.name}</h6>
              <span class="text-gray-600">${meal.totalCalories} kkal</span>
            </div>
            ${meal.items
              .map(
                (item) => `
              <div class="flex items-center justify-between mb-3">
                <div>
                  <h6 class="text-sm font-semibold text-gray-700">${item.name}</h6>
                  <small class="text-gray-500">${item.portion}</small>
                </div>
                <div class="text-right">
                  <span class="block text-gray-700">${item.calories} kkal</span>
                  <div class="flex mt-1 space-x-2">
                    <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">K: ${item.carbs}g</span>
                    <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">P: ${item.protein}g</span>
                    <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">L: ${item.fat}g</span>
                  </div>
                </div>
              </div>
            `
              )
              .join("")}
          </div>
        `
              )
              .join("")
      }
    </div>
  `;
  }


  updatePage();
  setInterval(updatePage, 5000);

  // Water Consumption Logic
  let currentWaterGlasses = 0; // Initial value from the HTML
  const targetWaterGlasses = 8;
  const waterPerGlassMl = 250; // Assuming 1 glass = 250ml

  const waterAddButton = document.querySelector('.bg-cyan-500.hover\\:bg-cyan-600');
  const waterCountText = document.querySelector('.px-4.py-5.text-center p');
  const waterProgressBar = document.querySelector('.h-3.rounded-full.bg-cyan-400');
  const waterGlassIcons = document.querySelectorAll('.fas.fa-glass-water');

  console.log('Water Consumption Elements:');
  console.log('Button:', waterAddButton);
  console.log('Count Text:', waterCountText);
  console.log('Progress Bar:', waterProgressBar);
  console.log('Glass Icons:', waterGlassIcons);

  function updateWaterDisplay() {
    console.log('updateWaterDisplay called. currentWaterGlasses:', currentWaterGlasses);
    // Ensure currentWaterGlasses does not exceed target
    if (currentWaterGlasses > targetWaterGlasses) {
      currentWaterGlasses = targetWaterGlasses;
    }

    const currentWaterMl = currentWaterGlasses * waterPerGlassMl;
    const targetWaterMl = targetWaterGlasses * waterPerGlassMl;
    const percentage = (currentWaterGlasses / targetWaterGlasses) * 100;

    waterCountText.textContent = `${currentWaterGlasses} dari ${targetWaterGlasses} gelas (${currentWaterMl}ml)`;
    waterProgressBar.style.width = `${percentage}%`;

    waterGlassIcons.forEach((icon, index) => {
      if (index < currentWaterGlasses) {
        icon.classList.remove('text-gray-400');
        icon.classList.add('text-cyan-500');
      } else {
        icon.classList.remove('text-cyan-500');
        icon.classList.add('text-gray-400');
      }
    });

    // Disable button if target reached
    if (currentWaterGlasses >= targetWaterGlasses) {
      waterAddButton.disabled = true;
      waterAddButton.classList.remove('hover:bg-cyan-600');
      waterAddButton.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
      waterAddButton.disabled = false;
      waterAddButton.classList.add('hover:bg-cyan-600');
      waterAddButton.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    console.log('Water display updated. Percentage:', percentage, 'Current ML:', currentWaterMl);
  }

  waterAddButton.addEventListener('click', () => {
    console.log('Tambah Air button clicked!');
    if (currentWaterGlasses < targetWaterGlasses) {
      currentWaterGlasses++;
      updateWaterDisplay();
    }
  });

  // Initial display update
  updateWaterDisplay();
</script>
