<div class="p-6 bg-white rounded-lg shadow-md welcome-section">
  <div>
    <h4 class="text-xl font-semibold">Welcome back, <?= $_SESSION['username'] ?></h4>
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


  <div class="chart-container">
    <canvas id="nutritionChart"></canvas>
  </div>
</div>

<script>
  let myChart;

  // konsumsi nutrisi selama seminggu 
  const consumptionData = <?= json_encode($weeklyFoodData) ?>

  // Fungsi untuk mendapatkan kategori BMI
  function getBMICategory(bmi) {
    if (bmi < 18.5) return "Berat Badan Kurang";
    if (bmi < 25) return "Berat Badan Normal";
    if (bmi < 30) return "Berat Badan Berlebih";
    return "Obesitas";
  }

  // Fungsi untuk menghitung kebutuhan nutrisi harian
  function calculateDailyNeeds(tdee) {
    // Persentase umum untuk pembagian makronutrien (bisa disesuaikan)
    return {
      calories: tdee,
      protein: (tdee * 0.3) / 4, // 30% dari kalori, 1g protein = 4 kalori
      carbs: (tdee * 0.45) / 4, // 45% dari kalori, 1g karbohidrat = 4 kalori
      fat: (tdee * 0.25) / 9 // 25% dari kalori, 1g lemak = 9 kalori
    };
  }

  // Fungsi untuk membuat atau memperbarui grafik
  function updateChart(dailyNeeds) {
    const ctx = document.getElementById('nutritionChart').getContext('2d');
    const labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    // Data kebutuhan harian (garis lurus karena kebutuhan harian tetap)
    const needsData = {
      calories: Array(7).fill(dailyNeeds.calories),
      protein: Array(7).fill(dailyNeeds.protein),
      carbs: Array(7).fill(dailyNeeds.carbs),
      fat: Array(7).fill(dailyNeeds.fat)
    };

    // Jika sudah ada grafik, hancurkan dulu
    if (myChart) {
      myChart.destroy();
    }

    // Buat grafik baru
    myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
            label: 'Kalori Dikonsumsi',
            data: consumptionData.calories,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
          },
          {
            label: 'Kebutuhan Kalori',
            data: needsData.calories,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            borderDash: [5, 5]
          },
          {
            label: 'Protein Dikonsumsi (g)',
            data: consumptionData.protein,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            hidden: true,
            tension: 0.1
          },
          {
            label: 'Kebutuhan Protein (g)',
            data: needsData.protein,
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.1)',
            borderDash: [5, 5],
            hidden: true
          }
          // Karbohidrat dan lemak bisa ditambahkan dengan cara yang sama
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

  // Fungsi utama untuk menghitung semua nilai dan memperbarui UI
  function calculateAndUpdate() {
    const gender = "male";
    const age = <?= $_SESSION['umur'] ?>;
    const height = <?= $_SESSION['tinggi_badan'] ?>;
    const weight = <?= $_SESSION['berat_badan'] ?>;
    const activityLevel = 1.2;

    // Hitung BMI dan BMR
    const bmi = <?= $_SESSION['bmi'] ?>;
    const bmiCategory = getBMICategory(<?= $_SESSION['bmi'] ?>);
    const bmr = <?= $_SESSION['bmr'] ?>;
    const tdee = <?= $_SESSION['tdee'] ?>;

    console.log(bmi, bmiCategory, bmr, tdee);

    // Hitung kebutuhan nutrisi harian
    const dailyNeeds = calculateDailyNeeds(tdee);

    // Perbarui grafik
    updateChart(dailyNeeds);
  }

  // Jalankan perhitungan dan pembuatan grafik saat halaman dimuat
  document.addEventListener('DOMContentLoaded', function() {
    calculateAndUpdate();
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>