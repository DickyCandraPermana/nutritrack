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
</div>