<div class="p-6 bg-white rounded-lg shadow-md welcome-section">
  <div>
    <h4 class="text-xl font-semibold">Welcome back, <?= $user['username'] ?></h4>
    <p class="text-sm text-gray-500"><?= date('l, j F Y') ?></p>

    <div class="flex justify-center gap-4 mt-4">
      <div class="px-4 py-2 text-red-600 bg-red-100 rounded-lg shadow tracker-card calories">5490 cal</div>
      <div class="px-4 py-2 text-yellow-600 bg-yellow-100 rounded-lg shadow tracker-card carbs">5490 carb</div>
      <div class="px-4 py-2 text-pink-600 bg-pink-100 rounded-lg shadow tracker-card heart">5490 bpm</div>
      <div class="px-4 py-2 text-blue-600 bg-blue-100 rounded-lg shadow tracker-card water">5490 oz</div>
      <div class="px-4 py-2 text-green-500 bg-green-100 rounded-lg shadow tracker-card exercise">5490 min</div>
    </div>
  </div>

  <button class="px-6 py-2 mt-4 text-white transition bg-black rounded-full hover:bg-gray-800">Create New Plan</button>

  <?php if ($user['bmi'] && $user['bmr'] && $user['tdee']): ?>
    <div class="mt-6 chart-container">
      <canvas id="nutritionChart"></canvas>
    </div>
  <?php else: ?>
    <h1 class="mt-6 text-center text-red-600">Belum memasukkan data diri</h1>
  <?php endif; ?>
</div>

<script>
  let myChart;

  const consumptionData = <?= json_encode($weeklyFoodData) ?>;

  function getBMICategory(bmi) {
    if (bmi < 18.5) return "Berat Badan Kurang";
    if (bmi < 25) return "Berat Badan Normal";
    if (bmi < 30) return "Berat Badan Berlebih";
    return "Obesitas";
  }

  function calculateDailyNeeds(tdee) {
    return {
      calories: tdee,
      protein: (tdee * 0.3) / 4,
      carbs: (tdee * 0.45) / 4,
      fat: (tdee * 0.25) / 9
    };
  }

  function updateChart(dailyNeeds) {
    const ctx = document.getElementById('nutritionChart').getContext('2d');
    const labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    const needsData = {
      calories: Array(7).fill(dailyNeeds.calories),
      protein: Array(7).fill(dailyNeeds.protein),
      carbs: Array(7).fill(dailyNeeds.carbs),
      fat: Array(7).fill(dailyNeeds.fat)
    };

    if (myChart) myChart.destroy();

    myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
            label: 'Kalori Dikonsumsi',
            data: consumptionData.calories,
            borderColor: '#FF6B6B',
            backgroundColor: 'rgba(255, 107, 107, 0.2)',
            tension: 0.1
          },
          {
            label: 'Kebutuhan Kalori',
            data: needsData.calories,
            borderColor: '#C92A2A',
            backgroundColor: 'rgba(201, 42, 42, 0.1)',
            borderDash: [5, 5]
          },
          {
            label: 'Protein Dikonsumsi (g)',
            data: consumptionData.protein,
            borderColor: '#4C6EF5',
            backgroundColor: 'rgba(76, 110, 245, 0.2)',
            tension: 0.1
          },
          {
            label: 'Kebutuhan Protein (g)',
            data: needsData.protein,
            borderColor: '#364FC7',
            backgroundColor: 'rgba(54, 79, 199, 0.1)',
            borderDash: [5, 5]
          },
          {
            label: 'Karbohidrat Dikonsumsi (g)',
            data: consumptionData.carbs,
            borderColor: '#40C057',
            backgroundColor: 'rgba(64, 192, 87, 0.2)',
            tension: 0.1
          },
          {
            label: 'Kebutuhan Karbohidrat (g)',
            data: needsData.carbs,
            borderColor: '#2B8A3E',
            backgroundColor: 'rgba(43, 138, 62, 0.1)',
            borderDash: [5, 5]
          },
          {
            label: 'Lemak Dikonsumsi (g)',
            data: consumptionData.fat,
            borderColor: '#FAB005',
            backgroundColor: 'rgba(250, 176, 5, 0.2)',
            tension: 0.1
          },
          {
            label: 'Kebutuhan Lemak (g)',
            data: needsData.fat,
            borderColor: '#E67700',
            backgroundColor: 'rgba(230, 119, 0, 0.1)',
            borderDash: [5, 5]
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: false
          }
        }
      }
    });
  }

  function calculateAndUpdate() {
    const age = <?= hitungUmur($user['tanggal_lahir']) ?>;
    const height = <?= $user['tinggi_badan'] ?>;
    const weight = <?= $user['berat_badan'] ?>;
    const bmi = <?= $user['bmi'] ?>;
    const bmr = <?= $user['bmr'] ?>;
    const tdee = <?= $user['tdee'] ?>;

    console.log(bmi, getBMICategory(bmi), bmr, tdee);
    const dailyNeeds = calculateDailyNeeds(tdee);
    updateChart(dailyNeeds);
  }

  document.addEventListener('DOMContentLoaded', calculateAndUpdate);
</script>