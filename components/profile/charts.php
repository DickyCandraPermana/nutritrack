<?php if (isset($user['bmi']) && isset($user['bmr']) && isset($user['tdee'])): ?>
  <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2">
    <div class="h-64 p-4 rounded-lg shadow-lg chart-container">
      <h5 class="mb-2 font-semibold text-center">Grafik Kalori Harian</h5>
      <canvas id="calorieChart" class="w-full h-full"></canvas>
    </div>
    <div class="h-64 p-4 rounded-lg shadow-lg chart-container">
      <h5 class="mb-2 font-semibold text-center">Grafik Karbohidrat Harian</h5>
      <canvas id="carbChart" class="w-full h-full"></canvas>
    </div>
    <div class="h-64 p-4 rounded-lg shadow-lg chart-container">
      <h5 class="mb-2 font-semibold text-center">Grafik Protein Harian</h5>
      <canvas id="proteinChart" class="w-full h-full"></canvas>
    </div>
    <div class="h-64 p-4 rounded-lg shadow-lg chart-container">
      <h5 class="mb-2 font-semibold text-center">Grafik Lemak Harian</h5>
      <canvas id="fatChart" class="w-full h-full"></canvas>
    </div>
  </div>
<?php else: ?>
  <h1 class="mt-6 text-center text-red-600 ">Belum memasukkan data diri</h1>
<?php endif; ?>
</div>
</div>

<script>
  let calorieChart, carbChart, proteinChart, fatChart;
  const consumptionData = <?= json_encode($weeklyFoodData) ?>;

  function getLast7Days() {
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const today = new Date();
    const result = [];

    for (let i = 6; i >= 0; i--) {
      const pastDate = new Date(today);
      pastDate.setDate(today.getDate() - i);
      result.push(days[pastDate.getDay()]);
    }

    return result;
  }


  function calculateDailyNeeds(tdee) {
    return {
      calories: tdee,
      protein: (tdee * 0.3) / 4,
      carbs: (tdee * 0.45) / 4,
      fat: (tdee * 0.25) / 9
    };
  }

  function renderLineChart(canvasId, consumed, needed, label1, label2, colors) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    if (window[canvasId + 'Instance']) {
      window[canvasId + 'Instance'].destroy();
    }
    window[canvasId + 'Instance'] = new Chart(ctx, {
      type: 'line',
      data: {
        labels: getLast7Days(),
        datasets: [{
            label: label1,
            data: consumed,
            borderColor: colors[0],
            backgroundColor: colors[1],
            tension: 0.1
          },
          {
            label: label2,
            data: needed,
            borderColor: colors[2],
            backgroundColor: colors[3],
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
    const tdee = <?= $user['tdee'] ?>;
    const dailyNeeds = calculateDailyNeeds(tdee);

    renderLineChart(
      'calorieChart',
      consumptionData.calories,
      Array(7).fill(dailyNeeds.calories),
      'Kalori Dikonsumsi',
      'Kebutuhan Kalori',
      ['#FF6B6B', 'rgba(255, 107, 107, 0.2)', '#C92A2A', 'rgba(201, 42, 42, 0.1)']
    );

    renderLineChart(
      'carbChart',
      consumptionData.carbs,
      Array(7).fill(dailyNeeds.carbs),
      'Karbohidrat Dikonsumsi (g)',
      'Kebutuhan Karbohidrat (g)',
      ['#40C057', 'rgba(64, 192, 87, 0.2)', '#2B8A3E', 'rgba(43, 138, 62, 0.1)']
    );

    renderLineChart(
      'proteinChart',
      consumptionData.protein,
      Array(7).fill(dailyNeeds.protein),
      'Protein Dikonsumsi (g)',
      'Kebutuhan Protein (g)',
      ['#4C6EF5', 'rgba(76, 110, 245, 0.2)', '#364FC7', 'rgba(54, 79, 199, 0.1)']
    );

    renderLineChart(
      'fatChart',
      consumptionData.fat,
      Array(7).fill(dailyNeeds.fat),
      'Lemak Dikonsumsi (g)',
      'Kebutuhan Lemak (g)',
      ['#FAB005', 'rgba(250, 176, 5, 0.2)', '#E67700', 'rgba(230, 119, 0, 0.1)']
    );
  }

  document.addEventListener('DOMContentLoaded', calculateAndUpdate);
</script>